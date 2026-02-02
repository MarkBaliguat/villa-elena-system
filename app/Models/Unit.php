<?php
// app/Models/Unit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Unit extends Model
{
    use HasFactory;

    protected $primaryKey = 'unitID';
    public $incrementing = true;

    protected $fillable = [
        'unitName',
        'unitType',
        'description',
        'capacity',
        'images',
        'unitRatePrice',
        'unitStatus',
        'blockStartDate',
        'blockEndDate',
        'blockReason',
        'for_special_events',
        'virtualTourPanorama',
    ];

    protected $casts = [
        'images' => 'array',
        'unitRatePrice' => 'decimal:2',
        'blockStartDate' => 'date',
        'blockEndDate' => 'date',
    ];

    // Accessor para sa images
    public function getImagesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Mutator para sa images
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        } else {
            $this->attributes['images'] = $value;
        }
    }

    // Get first image URL
    public function getFirstImageUrlAttribute()
    {
        $images = $this->images;
        if (!empty($images) && is_array($images)) {
            $firstImage = $images[0] ?? '';
            if ($firstImage && Storage::disk('public')->exists($firstImage)) {
                return Storage::url($firstImage);
            }
        }
        return null;
    }

    // Get all image URLs
    public function getImageUrlsAttribute()
    {
        $urls = [];
        $images = $this->images;
        
        if (!empty($images) && is_array($images)) {
            foreach ($images as $image) {
                if ($image && Storage::disk('public')->exists($image)) {
                    $urls[] = Storage::url($image);
                }
            }
        }
        
        return $urls;
    }

      public function getVirtualTourUrlAttribute()
    {
        if (!$this->virtualTourPanorama) {
            return null;
        }
        
        return url('/virtual-tour?panorama=' . $this->virtualTourPanorama);
    }

    /**
     * Check if unit has virtual tour
     */
    public function hasVirtualTour()
    {
        return !empty($this->virtualTourPanorama);
    }
    
    // Relationships
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'unitID');
    }
    public function bookings()
    {
        // You'll need to define this relationship based on your booking structure
        return $this->hasMany(Booking::class, 'unitID', 'unitID');
    }
    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('unitStatus', 'available');
    }

    public function scopeRooms($query)
    {
        return $query->where('unitType', 'room');
    }

    public function scopeCottages($query)
    {
        return $query->where('unitType', 'cottage');
    }

    public function isBlocked()
    {
        return $this->unitStatus === 'blocked';
    }
}