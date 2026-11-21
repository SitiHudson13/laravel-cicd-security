<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    // LAB 7 (Part A3): deliberately over-permissive. Part C3 restricts this to ['description'].
    protected $fillable = [
        'user_id',
        'reference',
        'description',
        'total_cents',
        'status',
        'payment_reference',
        'internal_notes',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
