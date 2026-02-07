<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventParticipant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_id',
        'full_name',
        'phone',
        'payment_status',
        'seat_number',
        'group_label',
        'notes',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function hasCheckedIn(): bool
    {
        return $this->attendances()->exists();
    }
}
