<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $table = 'payment_history';

    protected $fillable = [
        'payment_id',
        'pilgrim_id',
        'action',
        'old_data',
        'new_data',
        'performed_by',
        'ip_address',
        'user_agent',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'old_data' => 'json',
            'new_data' => 'json',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function pilgrim()
    {
        return $this->belongsTo(Pilgrim::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // Enregistrer une action
    public static function logAction($payment, $action, $oldData = null, $newData = null, $notes = null)
    {
        return static::create([
            'payment_id' => $payment->id,
            'pilgrim_id' => $payment->pilgrim_id,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'performed_by' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'notes' => $notes,
        ]);
    }

    // Actions disponibles
    const ACTIONS = [
        'created' => 'Paiement créé',
        'updated' => 'Paiement modifié',
        'deleted' => 'Paiement supprimé',
        'transferred' => 'Transféré en banque',
        'cancelled' => 'Paiement annulé',
        'validated' => 'Paiement validé',
        'receipt_generated' => 'Reçu généré',
        'receipt_printed' => 'Reçu imprimé',
    ];

    public function getActionLabelAttribute()
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('performed_by', $userId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}