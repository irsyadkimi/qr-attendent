<?php
namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Contact;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class PublicEventController extends Controller {
    public function checkPhone(Request $request) {
        $contact = Contact::where('phone', $request->phone)->first();
        if ($contact) {
            return response()->json([
                'status' => 'found',
                'name' => $contact->name,
                'address' => $contact->address
            ]);
        }
        return response()->json(['status' => 'not_found']);
    }

    public function store(Request $request, Event $event) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $contact = Contact::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->name, 'address' => $request->address, 'is_member' => false]
        );

        EventParticipant::create([
            'event_id' => $event->id,
            'contact_id' => $contact->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('public.event.success', $event);
    }
}
