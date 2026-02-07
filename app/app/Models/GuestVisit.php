<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GuestVisit extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'full_name',
        'phone',
        'organization_name',
        'organization_type',
        'agenda',
        'location',
        'group_count',
        'notes',
        'visited_at',
        'visit_end_date',
        'duration_days',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
            'visit_end_date' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($visit) {
            // Auto-calculate duration if end date provided
            if ($visit->visit_end_date && $visit->visited_at) {
                $start = Carbon::parse($visit->visited_at)->startOfDay();
                $end = Carbon::parse($visit->visit_end_date)->startOfDay();
                $visit->duration_days = $start->diffInDays($end) + 1;
            } else {
                $visit->duration_days = 1;
            }
        });
    }

    public function getDurationTextAttribute(): string
    {
        if ($this->duration_days && $this->duration_days > 1) {
            return "{$this->duration_days} hari";
        }
        return "1 hari";
    }
}
