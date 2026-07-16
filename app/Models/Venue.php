<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'price_per_hour',
        'location',
        'image_path',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'price_per_hour' => 'decimal:2',
    ];

    /**
     * Get the bookings for the venue.
     */
    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
