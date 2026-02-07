<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicEventController extends Controller
{
    public function show(string $event_code): View
    {
        $event = Event::where('event_code', $event_code)->firstOrFail();

        return view('public.event-checkin', compact('event'));
    }

    public function checkPhone(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = $this->normalizePhone($validated['phone']);
        $contact = Contact::where('phone', $phone)->first();

        if (! $contact) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json([
            'status' => 'found',
            'name' => $contact->full_name,
            'address' => $contact->address,
        ]);
    }

    public function store(Request $request, string $event_code): RedirectResponse
    {
        $event = Event::where('event_code', $event_code)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        $phone = $this->normalizePhone($validated['phone']);

        DB::transaction(function () use ($event, $validated, $phone): void {
            $contact = Contact::firstOrCreate(
                ['phone' => $phone],
                [
                    'full_name' => $validated['name'],
                    'address' => $validated['address'],
                    'is_member' => false,
                ],
            );

            EventParticipant::create([
                'event_id' => (string) $event->id,
                'contact_id' => (string) $contact->id,
                'full_name' => $validated['name'],
                'phone' => $phone,
            ]);
        });

        return redirect()->route('event.success', $event->event_code);
    }

    public function success(string $event_code): View
    {
        $event = Event::where('event_code', $event_code)->firstOrFail();

        return view('public.event-success', compact('event'));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\s+/', '', trim($phone));
    }
}
