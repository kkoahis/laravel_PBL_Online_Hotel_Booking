<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotel';

    protected $fillable = [
        'name',
        'address',
        'hotline',
        'email',
        'description',
        'room_total',
        'parking_slot',
        'bathrooms',
        'rating',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function hotelImage()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }
}
