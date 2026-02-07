<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Warning Banner --}}
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-red-800">DANGER ZONE - Reset Database</h3>
                    <p class="mt-2 text-sm text-red-700 font-semibold">⚠️ Data yang dihapus TIDAK DAPAT dikembalikan!</p>
                </div>
            </div>
        </div>

        {{-- Confirmation Form --}}
        <x-filament::section>
            <x-slot name="heading">
                Pilih Data yang Akan Direset
            </x-slot>
            <x-slot name="description">
                Centang tabel yang ingin direset. Anda harus memasukkan password akun DUA KALI untuk konfirmasi.
            </x-slot>

            <form wire:submit.prevent="resetDatabase" class="space-y-6">
                {{-- Selection Checkboxes --}}
                <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">Pilih Data untuk Direset:</p>
                    
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg border-2 border-gray-200 hover:border-sky-300 cursor-pointer transition">
                        <input type="checkbox" wire:model="reset_guest_visits" class="mt-1">
                        <div>
                            <p class="font-semibold text-gray-800">Buku Tamu Permanen</p>
                            <p class="text-xs text-gray-600">Hapus semua kunjungan tamu ({{ \App\Models\GuestVisit::count() }} records)</p>
                        </div>
                    </label>
                    
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg border-2 border-gray-200 hover:border-sky-300 cursor-pointer transition">
                        <input type="checkbox" wire:model="reset_attendances" class="mt-1">
                        <div>
                            <p class="font-semibold text-gray-800">Data Attendance (Check-in Event)</p>
                            <p class="text-xs text-gray-600">Hapus semua attendance event ({{ \App\Models\Attendance::count() }} records)</p>
                        </div>
                    </label>
                    
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg border-2 border-gray-200 hover:border-sky-300 cursor-pointer transition">
                        <input type="checkbox" wire:model="reset_participants" class="mt-1">
                        <div>
                            <p class="font-semibold text-gray-800">Roster Peserta Event</p>
                            <p class="text-xs text-gray-600">Hapus semua roster peserta ({{ \App\Models\EventParticipant::count() }} records)</p>
                        </div>
                    </label>
                    
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg border-2 border-red-200 hover:border-red-400 cursor-pointer transition">
                        <input type="checkbox" wire:model="reset_events" class="mt-1">
                        <div>
                            <p class="font-semibold text-red-800">Semua Events (+ Roster + Attendance)</p>
                            <p class="text-xs text-red-600">Hapus SEMUA event beserta roster & attendance-nya ({{ \App\Models\Event::count() }} events)</p>
                        </div>
                    </label>
                </div>

                {{-- Password Confirmation --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password (Pertama)
                        </label>
                        <input type="password" 
                               wire:model="password_confirmation_1" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                               placeholder="Masukkan password Anda">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password (Kedua)
                        </label>
                        <input type="password" 
                               wire:model="password_confirmation_2" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                               placeholder="Masukkan password yang sama sekali lagi">
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Tips:</strong> Backup database sebelum reset! Export data penting via Excel terlebih dahulu.
                    </p>
                </div>

                <div class="flex gap-4">
                    <x-filament::button 
                        type="submit" 
                        color="danger"
                        icon="heroicon-o-trash"
                        size="lg">
                        Reset Database yang Dipilih
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Statistics --}}
        <x-filament::section>
            <x-slot name="heading">
                Statistik Database Saat Ini
            </x-slot>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600">Events</p>
                    <p class="text-2xl font-bold text-blue-900">{{ \App\Models\Event::count() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600">Roster</p>
                    <p class="text-2xl font-bold text-green-900">{{ \App\Models\EventParticipant::count() }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-yellow-600">Attendance</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ \App\Models\Attendance::count() }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-600">Buku Tamu</p>
                    <p class="text-2xl font-bold text-purple-900">{{ \App\Models\GuestVisit::count() }}</p>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
