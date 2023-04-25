<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';

    protected $fillable = [
        'hotel_id',
        'category_id',
        'name',
        'size',
        'bed',
        'bathroom_facilities',
        'amenities',
        'direction_view',
        'description',
        'status',
        'max_people',
        'price',
        'is_smoking',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function roomImage()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function bookingDetail()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
