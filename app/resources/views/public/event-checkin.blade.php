<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Event</h1>
            <p class="text-gray-600 mb-6">{{ $event->event_name }}</p>

            <form action="{{ route('event.checkin', $event->event_code) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP *</label>
                    <div class="flex gap-2">
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="08xxxxxxxxxx"
                        >
                        <button
                            type="button"
                            id="check-phone-button"
                            class="px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700"
                        >
                            Cek
                        </button>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat *</label>
                    <textarea
                        name="address"
                        id="address"
                        required
                        rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700"
                >
                    Daftar Hadir
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('check-phone-button').addEventListener('click', async () => {
            const phoneInput = document.getElementById('phone');
            const nameInput = document.getElementById('name');
            const addressInput = document.getElementById('address');
            const phone = phoneInput.value.trim();

            if (!phone) {
                alert('Isi nomor HP terlebih dahulu.');
                return;
            }

            try {
                const response = await fetch(`/api/check-phone?phone=${encodeURIComponent(phone)}`);
                const data = await response.json();

                if (data.status === 'found') {
                    nameInput.value = data.name ?? '';
                    addressInput.value = data.address ?? '';
                    alert('Data kontak ditemukan dan diisi otomatis.');
                } else {
                    alert('Nomor belum terdaftar, silakan isi data manual.');
                }
            } catch (error) {
                alert('Gagal menghubungi server. Silakan coba lagi.');
            }
        });
    </script>
</body>
</html>
