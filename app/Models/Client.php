<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'emergency_phone',
        'passport_number',
        'passport_expiry_date',
        'nationality',
        'region',
        'department',
        'notes',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'passport_expiry_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function pilgrims()
    {
        return $this->hasMany(Pilgrim::class);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getTotalPilgrimagesAttribute()
    {
        return $this->pilgrims()->count();
    }

    public function getCompletedPilgrimagesAttribute()
    {
        return $this->pilgrims()->where('status', 'paid')->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->pilgrims()->with('payments')->get()->sum(function ($pilgrim) {
            return $pilgrim->payments->where('status', 'completed')->sum('amount');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByName($query, $name)
    {
        return $query->where(function ($q) use ($name) {
            $q->where('firstname', 'like', '%' . $name . '%')
              ->orWhere('lastname', 'like', '%' . $name . '%');
        });
    }

    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', 'like', '%' . $phone . '%');
    }
}
