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
        'transferred_to_bank',
        'bank_transfer_date',
        'bank_transfer_reference',
        'bank_transfer_notes',
        'transferred_by',
        'receipt_number',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'transferred_to_bank' => 'boolean',
            'bank_transfer_date' => 'date',
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

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function client()
    {
        return $this->hasOneThrough(Client::class, Pilgrim::class, 'id', 'id', 'pilgrim_id', 'client_id');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopeTransferredToBank($query)
    {
        return $query->where('transferred_to_bank', true);
    }

    public function scopeNotTransferredToBank($query)
    {
        return $query->where('transferred_to_bank', false);
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
