<x-filament-panels::page>
    <div class="grid gap-6">
        <x-filament::section>
            <x-slot name="heading">
                QR Code Buku Tamu Permanen
            </x-slot>
            <x-slot name="description">
                QR Code ini berlaku untuk 1-2 tahun ke depan. Print dan tempel di area resepsionis/pintu masuk.
            </x-slot>
            
            <div class="flex flex-col items-center gap-6 py-8">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    {!! QrCode::size(400)->generate(url('/tamu')) !!}
                </div>
                
                <div class="text-center space-y-2">
                    <p class="text-lg font-semibold text-gray-700">Link: {{ url('/tamu') }}</p>
                    <p class="text-sm text-gray-500">Scan QR atau akses link untuk isi buku tamu</p>
                </div>
                
                <div class="flex gap-4">
                    <x-filament::button 
                        color="success"
                        icon="heroicon-o-arrow-down-tray"
                        tag="a"
                        href="{{ route('guest.qr.download') }}"
                        target="_blank">
                        Download QR Code PNG
                    </x-filament::button>
                    
                    <x-filament::button 
                        color="info"
                        icon="heroicon-o-clipboard-document"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText('{{ url('/tamu') }}'); copied = true; setTimeout(() => copied = false, 2000)">
                        <span x-show="!copied">Copy Link</span>
                        <span x-show="copied">Copied!</span>
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
        
        <x-filament::section>
            <x-slot name="heading">
                Statistik Kunjungan
            </x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 font-medium">Hari Ini</p>
                    <p class="text-2xl font-bold text-blue-900">{{ \App\Models\GuestVisit::whereDate('visited_at', today())->count() }}</p>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium">Minggu Ini</p>
                    <p class="text-2xl font-bold text-green-900">{{ \App\Models\GuestVisit::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</p>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-yellow-600 font-medium">Bulan Ini</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ \App\Models\GuestVisit::whereMonth('visited_at', now()->month)->count() }}</p>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-purple-600 font-medium">Total</p>
                    <p class="text-2xl font-bold text-purple-900">{{ \App\Models\GuestVisit::count() }}</p>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
