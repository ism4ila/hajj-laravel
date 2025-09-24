<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pilgrim extends Model
{
    protected $fillable = [
        'campaign_id',
        'firstname',
        'lastname',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'emergency_phone',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
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

    public function updateAmounts()
    {
        $this->total_amount = $this->campaign->price;
        $this->paid_amount = $this->payments()->sum('amount');
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
        $this->save();
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }
}
