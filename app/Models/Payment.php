<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'pilgrim_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'notes',
        'status',
        'receipt_number',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function pilgrim()
    {
        return $this->belongsTo(Pilgrim::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->receipt_number) {
                $payment->receipt_number = 'REC-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });

        // Les montants sont maintenant calculés dynamiquement
    }
}
