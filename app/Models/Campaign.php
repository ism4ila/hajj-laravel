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
        'price_classic',
        'price_vip',
        'departure_date',
        'return_date',
        'description',
        'classic_description',
        'vip_description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'price_classic' => 'decimal:2',
            'price_vip' => 'decimal:2',
        ];
    }

    public function pilgrims()
    {
        return $this->hasMany(Pilgrim::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Obtenir le prix selon la catégorie
    public function getPriceForCategory($category)
    {
        return $category === 'vip' ? $this->price_vip : $this->price_classic;
    }

    // Vérifier si la campagne accepte de nouveaux pèlerins
    public function isOpenForRegistration()
    {
        return in_array($this->status, ['open', 'active']);
    }

    // Compter les pèlerins par catégorie
    public function getClassicPilgrimsCountAttribute()
    {
        return $this->pilgrims()->where('category', 'classic')->count();
    }

    public function getVipPilgrimsCountAttribute()
    {
        return $this->pilgrims()->where('category', 'vip')->count();
    }
}
