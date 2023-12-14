<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index()
    {
        return Inertia::render('Events/Index', [
            'filters' => Request::all('search', 'trashed'),
            'events' => Auth::user()->company->events()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($event) => [
                    'id' => $event->id,
                    'name' => $event->name,
                    'phone' => $event->phone,
                    'city' => $event->city,
                    'deleted_at' => $event->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Events/Create');
    }

    public function store()
    {
        Auth::user()->company->events()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('events')->with('success', 'Evento criado.');
    }

    public function edit(Event $event)
    {

        $qrCodes = QrCode::whereEventId($event->id)->with(['contact'])->get();

        return Inertia::render('Events/Edit', [
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'email' => $event->email,
                'phone' => $event->phone,
                'address' => $event->address,
                'city' => $event->city,
                'region' => $event->region,
                'country' => $event->country,
                'postal_code' => $event->postal_code,
                'deleted_at' => $event->deleted_at,
                'contacts' => $qrCodes->map(function ($qrCode) {
                    return [
                        'id' => $qrCode->contact->id,
                        'name' => $qrCode->contact->name,
                        'email' => $qrCode->contact->email,
                        'phone' => $qrCode->contact->phone,
                        'address' => $qrCode->contact->address,
                        'city' => $qrCode->contact->city,
                        'region' => $qrCode->contact->region,
                        'country' => $qrCode->contact->country,
                        'postal_code' => $qrCode->contact->postal_code,
                        'deleted_at' => $qrCode->contact->deleted_at,
                    ];
                })
            ],
        ]);
    }

    public function update(Event $event)
    {
        $event->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Evento atualizado.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return Redirect::back()->with('success', 'Evento deletado.');
    }

    public function restore(Event $event)
    {
        $event->restore();

        return Redirect::back()->with('success', 'Evento restaurado.');
    }
}
