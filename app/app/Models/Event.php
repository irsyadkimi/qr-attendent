<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_code',
        'event_name',
        'event_type',
        'agenda_topic',
        'location',
        'date_start',
        'date_end',
        'capacity_expected',
        'allow_manual_entry',
        'allow_preloaded_select',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
            'allow_manual_entry' => 'boolean',
            'allow_preloaded_select' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->event_code)) {
                $event->event_code = strtoupper(Str::random(8));
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getQrUrlAttribute(): string
    {
        return url("/e/{$this->event_code}");
    }
}
