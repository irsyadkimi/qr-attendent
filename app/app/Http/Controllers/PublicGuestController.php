<?php

namespace App\Http\Controllers;

use App\Models\GuestVisit;
use Illuminate\Http\Request;

class PublicGuestController extends Controller
{
    public function show()
    {
        return view('public.guest-checkin');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'organization_name' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:50',
            'agenda' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'group_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        GuestVisit::create([
            ...$validated,
            'visited_at' => now(),
        ]);

        return redirect()->route('guest.success');
    }

    public function success()
    {
        return view('public.guest-success');
    }
}
