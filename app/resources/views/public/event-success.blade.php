<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Check-in Berhasil!</h1>
            <p class="text-gray-600 mb-6">Terima kasih telah hadir di</p>
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h2 class="font-semibold text-lg text-blue-900">{{ $event->event_name }}</h2>
                <p class="text-sm text-blue-700 mt-1">{{ $event->location }}</p>
                <p class="text-sm text-blue-700">{{ $event->date_start->format('d M Y') }}</p>
            </div>

            <p class="text-sm text-gray-500">Anda dapat menutup halaman ini</p>
        </div>
    </div>
</body>
</html>
