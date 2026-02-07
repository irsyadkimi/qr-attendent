<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Buku Tamu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 text-center">
            <div class="mb-6">
                <div class="inline-block p-4 bg-green-100 rounded-full mb-4">
                    <svg class="mx-auto h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-3">Terima Kasih!</h1>
            <p class="text-gray-600 mb-6">Kunjungan Anda telah tercatat dalam buku tamu kami</p>
            
            <div class="bg-green-50 rounded-lg p-5 mb-6">
                <p class="text-sm text-green-800 font-medium">Selamat datang di PRM Wage</p>
                <p class="text-xs text-green-600 mt-2">{{ now()->format('d M Y, H:i') }} WIB</p>
            </div>

            <p class="text-sm text-gray-500">Anda dapat menutup halaman ini</p>
        </div>
    </div>
</body>
</html>
