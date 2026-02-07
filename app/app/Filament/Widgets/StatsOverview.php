<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\EventParticipant;
use App\Models\GuestVisit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $todayAttendance = Attendance::whereDate('checked_in_at', today())->count();
        $todayGuests = GuestVisit::whereDate('visited_at', today())->count();
        $totalEvents = Event::count();
        $activeEvents = Event::where('date_start', '<=', today())
            ->where(function($q) {
                $q->whereNull('date_end')
                  ->orWhere('date_end', '>=', today());
            })->count();

        return [
            Stat::make('Total Events', $totalEvents)
                ->description('Semua event yang pernah dibuat')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
                
            Stat::make('Event Aktif', $activeEvents)
                ->description('Event yang sedang berlangsung')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
                
            Stat::make('Check-in Event Hari Ini', $todayAttendance)
                ->description('Peserta event hari ini')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
                
            Stat::make('Tamu Hari Ini', $todayGuests)
                ->description('Kunjungan buku tamu hari ini')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary'),
        ];
    }
}
