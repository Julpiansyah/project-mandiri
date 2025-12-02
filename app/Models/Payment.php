<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'payment_method',
        'transaction_id',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
