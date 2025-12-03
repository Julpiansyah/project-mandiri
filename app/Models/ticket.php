<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'type',
        'description',
        'price',
        'quota',
        'active',
        'sales_start',
        'sales_end',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sales_start' => 'datetime',
        'sales_end' => 'datetime',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Accessor for status based on event date
    public function getStatusAttribute()
    {
        if (!$this->event) {
            return 'unknown';
        }

        $eventDate = Carbon::parse($this->event->start_date);

        if ($eventDate->isPast()) {
            return 'kadaluarsa';
        } else {
            return 'aktif';
        }
    }

    // Method to update active status based on event date
    public function updateStatusBasedOnDate()
    {
        if (!$this->event) {
            return;
        }

        $eventDate = Carbon::parse($this->event->start_date);
        $this->active = !$eventDate->isPast();
        $this->save();
    }

    // Scope for active tickets
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
