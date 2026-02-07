<x-filament-panels::page.simple>
    <div class="flex flex-col items-center mb-8">
        {{-- Logo Muhammadiyah --}}
        <div class="mb-6">
            <img src="{{ asset('images/logo-muhammadiyah.png') }}" 
                 alt="Logo Muhammadiyah" 
                 class="h-28 w-auto"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2240%22 fill=%22%230369a1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2220%22 font-weight=%22bold%22%3EM%3C/text%3E%3C/svg%3E'">
        </div>
        
        {{-- Header Besar PRM - BIRU --}}
        <div class="text-center mb-4 max-w-2xl">
            <h1 class="text-xl md:text-2xl font-bold text-sky-900 leading-tight">
                PIMPINAN RANTING MUHAMMADIYAH WAGE
            </h1>
            <p class="text-base md:text-lg font-semibold text-sky-800 mt-1">
                CABANG SEPANJANG DAERAH SIDOARJO
            </p>
        </div>
        
        <div class="w-full max-w-md border-t-2 border-sky-600 my-4"></div>
        
        <h2 class="text-xl font-bold text-gray-800">Sistem Kehadiran Digital</h2>
        <p class="text-sm text-gray-600 mt-1">Silakan login untuk melanjutkan</p>
    </div>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    
    {{-- Branding Footer --}}
    <div class="mt-6 text-center text-xs text-gray-500">
        <p class="mb-1">Dikembangkan oleh</p>
        <p class="font-semibold text-sky-700">ICMI</p>
        <p class="mt-2">© {{ date('Y') }} PRM Wage Muhammadiyah</p>
    </div>
</x-filament-panels::page.simple>
