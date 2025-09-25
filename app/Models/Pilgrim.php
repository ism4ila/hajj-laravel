<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pilgrim extends Model
{
    protected $fillable = [
        'campaign_id',
        'category',
        'firstname',
        'lastname',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'emergency_phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function documents()
    {
        return $this->hasOne(Document::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    // Calculer le montant total selon la catégorie
    public function getTotalAmountAttribute()
    {
        if (!$this->campaign) return 0;
        return $this->campaign->getPriceForCategory($this->category);
    }

    // Calculer le montant payé
    public function getPaidAmountAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    // Calculer le montant restant
    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    // Vérifier si le paiement est complet
    public function isPaymentComplete()
    {
        return $this->remaining_amount <= 0;
    }

    // Obtenir le pourcentage de paiement
    public function getPaymentPercentageAttribute()
    {
        if ($this->total_amount <= 0) return 0;
        return ($this->paid_amount / $this->total_amount) * 100;
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeWithIncompletePayments($query)
    {
        return $query->whereHas('campaign')->get()->filter(function ($pilgrim) {
            return $pilgrim->remaining_amount > 0;
        });
    }
}
