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

    /**
     * Computed attribute: apakah tiket/event terkait sudah kadaluarsa?
     * - Menggunakan `end_date` jika tersedia, fallback ke `start_date`.
     * - Perbandingan date-only terhadap hari ini, sehingga event pada hari ini tetap aktif.
     */
    public function getIsExpiredAttribute()
    {
        $event = $this->event;
        if (!$event) return false;

        $dateToCheck = $event->end_date ?? $event->start_date;
        if (empty($dateToCheck)) return false;

        try {
            $eventDate = \Carbon\Carbon::parse($dateToCheck)->toDateString();
        } catch (\Exception $e) {
            return false;
        }

        $today = \Carbon\Carbon::today()->toDateString();
        return ($eventDate < $today);
    }

    /**
     * Status berdasarkan tanggal event: aktif atau kadaluarsa
     */
    public function getStatusBasedOnDateAttribute()
    {
        return $this->is_expired ? 'kadaluarsa' : 'aktif';
    }
}
