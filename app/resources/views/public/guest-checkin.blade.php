<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu PRM WG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 to-blue-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-2xl p-8">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-sky-50 rounded-full mb-4">
                    <img src="{{ asset('images/logo-muhammadiyah.png') }}" 
                         alt="Logo Muhammadiyah" 
                         class="h-20 w-auto"
                         onerror="this.parentElement.innerHTML='<svg class=\'w-12 h-12 text-sky-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253\'></path></svg>'">
                </div>
                
                <div class="mb-4">
                    <h1 class="text-lg md:text-xl font-bold text-sky-900 leading-tight">
                        PIMPINAN RANTING MUHAMMADIYAH WAGE
                    </h1>
                    <p class="text-sm md:text-base font-semibold text-sky-800">
                        CABANG SEPANJANG DAERAH SIDOARJO
                    </p>
                </div>
                
                <div class="w-full border-t-2 border-sky-600 my-4"></div>
                
                <h2 class="text-2xl font-bold text-gray-800">Buku Tamu</h2>
                <p class="text-sm text-gray-600 mt-2">Silakan isi formulir kunjungan Anda</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('guest.checkin') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="full_name" required 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition" 
                           placeholder="Nama lengkap Anda">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No HP *</label>
                    <input type="tel" name="phone" required 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition" 
                           placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Organisasi</label>
                    <input list="org-list" name="organization_name" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition" 
                           placeholder="Ketik atau pilih organisasi">
                    <datalist id="org-list">
                        @foreach(\App\Models\Organization::active()->orderBy('name')->get() as $org)
                            <option value="{{ $org->name }}">{{ $org->type ? "({$org->type})" : '' }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Organisasi</label>
                    <select name="organization_type" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
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
                        <option value="Other">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Agenda / Keperluan</label>
                    <input type="text" name="agenda" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition" 
                           placeholder="Tujuan kunjungan">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Kunjungan</label>
                    <input type="text" name="location" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition" 
                           placeholder="Misal: Kantor Sekretariat, Ruang Rapat">
                </div>

                {{-- DURASI KUNJUNGAN SECTION --}}
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Durasi Kunjungan
                    </h3>
                    <p class="text-xs text-blue-700 mb-3">Untuk kunjungan lebih dari 1 hari (study tour, magang, dll), isi tanggal selesai</p>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" 
                                   name="visit_start_display" 
                                   value="{{ date('Y-m-d') }}"
                                   readonly
                                   class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-xs text-gray-500 mt-1">Hari ini (otomatis)</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" 
                                   name="visit_end_date"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 text-sm border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika 1 hari</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Rombongan</label>
                    <input type="number" name="group_count" min="1" value="1" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-sky-600 to-blue-700 hover:from-sky-700 hover:to-blue-800 text-white font-bold py-4 rounded-lg shadow-lg hover:shadow-xl transition duration-200 transform hover:-translate-y-0.5">
                    Submit Kunjungan
                </button>
            </form>

            <div class="text-center text-xs text-gray-400 mt-6">
                <p>Buku Tamu Digital PRM Wage</p>
                <p class="mt-1 text-gray-500">Powered by <span class="font-semibold">ICMI</span></p>
            </div>
        </div>
    </div>
</body>
</html>
