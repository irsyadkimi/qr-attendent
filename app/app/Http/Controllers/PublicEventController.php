<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicEventController extends Controller
{
    public function show($event_code)
    {
        $event = Event::where('event_code', $event_code)->firstOrFail();
        return view('public.event-checkin', compact('event'));
    }

    public function store(Request $request, $event_code)
    {
        $event = Event::where('event_code', $event_code)->firstOrFail();

        $validated = $request->validate([
            'event_participant_id' => 'nullable|exists:event_participants,id',
            'manual_name' => 'required_without:event_participant_id|string|max:255',
            'manual_phone' => 'required_without:event_participant_id|string|max:20',
            'manual_origin' => 'nullable|string|max:255',
            'manual_org_type' => 'nullable|string|max:50',
            'manual_org_name' => 'nullable|string|max:255',
            'group_count' => 'nullable|integer|min:1',
        ]);

        try {
            $attendance = Attendance::create([
                'event_id' => $event->id,
                'event_participant_id' => $validated['event_participant_id'] ?? null,
                'manual_name' => $validated['manual_name'] ?? null,
                'manual_phone' => $validated['manual_phone'] ?? null,
                'manual_origin' => $validated['manual_origin'] ?? null,
                'manual_org_type' => $validated['manual_org_type'] ?? null,
                'manual_org_name' => $validated['manual_org_name'] ?? null,
                'group_count' => $validated['group_count'] ?? null,
                'checked_in_at' => now(),
            ]);

            return redirect()->route('event.success', $event_code);
        } catch (\Exception $e) {
            return back()->with('error', 'Check-in gagal: ' . $e->getMessage());
        }
    }

    public function success($event_code)
    {
        $event = Event::where('event_code', $event_code)->firstOrFail();
        return view('public.event-success', compact('event'));
    }
}
