<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public Event Check-in Routes
Route::get('/e/{event_code}', [App\Http\Controllers\PublicEventController::class, 'show'])->name('event.show');
Route::post('/e/{event_code}', [App\Http\Controllers\PublicEventController::class, 'store'])->name('event.checkin');
Route::get('/e/{event_code}/success', [App\Http\Controllers\PublicEventController::class, 'success'])->name('event.success');

// Autocomplete API for roster
Route::get('/api/events/{event_code}/participants/search', function($event_code) {
    $query = request('q');
    $event = \App\Models\Event::where('event_code', $event_code)->firstOrFail();
    
    $participants = $event->participants()
        ->where('full_name', 'like', "%{$query}%")
        ->limit(10)
        ->get(['id', 'full_name', 'phone', 'payment_status', 'seat_number']);
    
    return response()->json($participants);
})->name('event.participants.search');

// Public Guest Visit (Buku Tamu Permanen)
Route::get('/tamu', [App\Http\Controllers\PublicGuestController::class, 'show'])->name('guest.show');
Route::post('/tamu', [App\Http\Controllers\PublicGuestController::class, 'store'])->name('guest.checkin');
Route::get('/tamu/success', [App\Http\Controllers\PublicGuestController::class, 'success'])->name('guest.success');

// Download QR Code Buku Tamu
Route::get('/guest/qr/download', function() {
    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
        ->size(800)
        ->margin(2)
        ->errorCorrection('H')
        ->generate(url('/tamu'));
    
    return response($qrCode, 200, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'attachment; filename="qr-code-buku-tamu-prm-wg.png"',
    ]);
})->name('guest.qr.download');

// Download QR Code Buku Tamu
Route::get('/guest/qr/download', function() {
    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
        ->size(800)
        ->margin(2)
        ->errorCorrection('H')
        ->generate(url('/tamu'));
    
    return response($qrCode, 200, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'attachment; filename="qr-code-buku-tamu-prm-wg.png"',
    ]);
})->name('guest.qr.download');
