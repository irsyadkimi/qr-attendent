<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'organization_name',
        'organization_type',
        'position',
        'address',
        'notes',
        'is_member',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_member' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
