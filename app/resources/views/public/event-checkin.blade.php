<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in: {{ $event->event_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $event->event_name }}</h1>
                <p class="text-sm text-gray-600">{{ $event->location }}</p>
                <p class="text-sm text-gray-600">{{ $event->date_start->format('d M Y') }}</p>
                <div class="mt-3 inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    {{ ucfirst($event->event_type) }}
                </div>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('event.checkin', $event->event_code) }}" class="space-y-4">
                @csrf

                @if($event->allow_preloaded_select && $event->participants()->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Nama dari Daftar (Opsional)</label>
                        <select name="event_participant_id" id="participant_select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Atau isi manual di bawah --</option>
                            @foreach($event->participants as $participant)
                                <option value="{{ $participant->id }}">{{ $participant->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($event->allow_manual_entry)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="manual_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Nama Anda">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No HP *</label>
                        <input type="tel" name="manual_phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="08xxxxxxxxxx">
                    </div>

                    @if($event->event_type === 'kunjungan')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Utusan Dari (Instansi/Perusahaan)</label>
                            <input type="text" name="manual_origin" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Nama instansi">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Rombongan</label>
                            <input type="number" name="group_count" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="1">
                        </div>
                    @endif

                    @if(in_array($event->event_type, ['pengajian', 'rapat']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organisasi</label>
                            <input list="org-list" name="manual_org_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Ketik atau pilih organisasi">
                            <datalist id="org-list">
                                @foreach(\App\Models\Organization::active()->orderBy('name')->get() as $org)
                                    <option value="{{ $org->name }}">{{ $org->type ? "({$org->type})" : '' }}</option>
                                @endforeach
                            </datalist>
                            <p class="text-xs text-gray-500 mt-1">Ketik untuk mencari atau masukkan nama organisasi baru</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Organisasi (Opsional)</label>
                            <select name="manual_org_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                <option value="PRM">PRM</option>
                                <option value="PCM">PCM</option>
                                <option value="PDM">PDM</option>
                                <option value="PWM">PWM</option>
                                <option value="PRA">PRA</option>
                                <option value="PCA">PCA</option>
                                <option value="PDA">PDA</option>
                                <option value="PWA">PWA</option>
                                <option value="External">External/Tamu</option>
                            </select>
                        </div>
                    @endif
                @endif

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition duration-200">
                    Check-in Sekarang
                </button>
            </form>

            <div class="text-center text-xs text-gray-500 mt-4">
                <p>Kode Event: {{ $event->event_code }}</p>
                <p class="mt-2 text-gray-400">Powered by <span class="font-semibold text-gray-600">ICMI</span></p>
            </div>
        </div>
    </div>
</body>
</html>
