<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'type',
        'year_hijri',
        'year_gregorian',
        'price',
        'quota',
        'departure_date',
        'return_date',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function pilgrims()
    {
        return $this->hasMany(Pilgrim::class);
    }

    public function getAvailablePlacesAttribute()
    {
        if (!$this->quota) return null;
        return $this->quota - $this->pilgrims()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
