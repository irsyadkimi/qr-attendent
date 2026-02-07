<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_id',
        'event_participant_id',
        'manual_name',
        'manual_phone',
        'manual_origin',
        'manual_org_type',
        'manual_org_name',
        'group_count',
        'answers_json',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'answers_json' => 'array',
            'checked_in_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(EventParticipant::class, 'event_participant_id');
    }

    public function isManualEntry(): bool
    {
        return is_null($this->event_participant_id);
    }

    public function getAttendeeNameAttribute(): string
    {
        return $this->participant?->full_name ?? $this->manual_name ?? 'Unknown';
    }
}
