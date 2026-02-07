<div class="flex flex-col items-center gap-4 p-4">
    <div class="bg-white p-4 rounded-lg shadow-sm">
        {!! QrCode::size(300)->generate($getRecord()->qr_url) !!}
    </div>
    <p class="text-sm text-gray-600 text-center">
        Kode Event: <strong>{{ $getRecord()->event_code }}</strong>
    </p>
</div>
