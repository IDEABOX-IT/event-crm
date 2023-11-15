<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
                    $company = Company::whereId(1)->first;
                    $event = Event::wherePaymentLink($payload->data->payment_link)->first;
                    $res = $company->contacts()->create([
                        'first_name' => $payload->data->object->customer_details->name ?? null,
                        'stripe_id' => $payload->data->customer,
                        'event_id' => $event->id,
                        'email' => $payload->data->object->customer_details->email ?? null,
                        'phone' => $payload->data->object->customer_details->phone ?? null,
                        'address' => $payload->data->object->customer_details->address->line1 ?? null,
                        'city' => $payload->data->object->customer_details->address->city ?? null,
                        'region' => $payload->data->object->customer_details->address->state ?? null,
                        'country' => $payload->data->object->customer_details->address->country ?? null,
                        'postal_code' => $payload->data->object->customer_details->address->postal_code ?? null,
                    ]);

                    ray($res);
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
