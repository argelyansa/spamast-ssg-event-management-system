<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSession extends Model
{
    protected $fillable = [
        'event_id',
        'period',
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'check_in_open',
        'check_out_open',
    ];

    protected function casts(): array
    {
        return [
            'check_in_open' => 'boolean',
            'check_out_open' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function isCheckInOpen(): bool
    {
        return $this->isOpen('check_in');
    }

    public function isCheckOutOpen(): bool
    {
        return $this->isOpen('check_out');
    }

    private function isOpen(string $type): bool
    {
        $flagColumn = "{$type}_open";
        $startColumn = "{$type}_start";
        $endColumn = "{$type}_end";

        $event = $this->event;

        if (!$event) {
            return false;
        }

        // Manual availability uses the Officer-controlled flag.
        if ($event->availability === 'manual') {
            return (bool) $this->{$flagColumn};
        }

        // Scheduled availability only applies on the event date.
        $currentTime = now();

        if (!$event->event_date || !$event->event_date->isSameDay($currentTime)) {
            return false;
        }

        $start = $this->{$startColumn};
        $end = $this->{$endColumn};

        if (!$start || !$end) {
            return false;
        }

        $startTime = $currentTime->copy()->setTimeFromTimeString($start);
        $endTime = $currentTime->copy()->setTimeFromTimeString($end);

        return $currentTime->betweenIncluded($startTime, $endTime);
    }
}
