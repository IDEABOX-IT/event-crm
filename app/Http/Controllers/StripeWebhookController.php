<?php

namespace App\Http\Controllers;

use App\Mail\SendTicket;
use App\Models\Contact;
use App\Models\Event;
use App\Models\QrCode;
use App\Services\TicketService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{

    public function handleWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();

            $event = Webhook::constructEvent($payload, $request->header('stripe-signature'), env('STRIPE_WEBHOOK_SECRET'));

            // Handle specific Stripe event types here
            switch ($event->type) {
                case 'checkout.session.completed':
                    try {
                        $decoded = json_decode($payload);
                        $decoded->data->object->payment_link = 'plink_1OBGM6Ls4BgVZmB8J70Um0De';
                        $event = Event::wherePaymentLink($decoded->data->object->payment_link)->first();
                        $res = Contact::whereEmail($decoded->data->object->customer_details->email)->first();

                        if (!$res) {
                            $res = Contact::create([
                                'first_name' => $decoded->data->object->customer_details->name ?? null,
                                'stripe_id' => $decoded->data->object->customer,
                                'company_id' => $event->company_id,
                                'email' => $decoded->data->object->customer_details->email ?? null,
                                'phone' => $decoded->data->object->customer_details->phone ?? null,
                                'address' => $decoded->data->object->customer_details->address->line1 ?? null,
                                'city' => $decoded->data->object->customer_details->address->city ?? null,
                                'region' => $decoded->data->object->customer_details->address->state ?? null,
                                'country' => $decoded->data->object->customer_details->address->country ?? null,
                                'postal_code' => $decoded->data->object->customer_details->address->postal_code ?? null,
                            ]);
                        }

                        $quantity = $decoded->data->object->amount_subtotal / $event->price;
                        $event = Event::wherePaymentLink($decoded->data->object->payment_link)->first();
                        $generatedTicketPaths = TicketService::createTickets($res, $quantity, $event->id);
                        $qrCodes = QrCode::whereIn('qrCodePath', $generatedTicketPaths)->with(['event'])->get();

                        // TODO make to send email to client.
                        Mail::to($res->email)->send(new SendTicket($res, $qrCodes->first()->event->time, $qrCodes));

                    } catch (Exception $e) {
                        Log::error($e->getMessage());
                    }
                    break;
                case 'payment_intent.succeeded':
                    // Handle payment success
                    break;
                default:
                    // Handle other event types or ignore them
            }
            return response()->json(['message' => 'Webhook received and processed.']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
