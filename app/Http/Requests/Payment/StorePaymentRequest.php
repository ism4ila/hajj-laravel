<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('payment_create');
    }

    public function rules(): array
    {
        return [
            'pilgrim_id' => 'required|exists:pilgrims,id',
            'amount' => 'required|numeric|min:0.01|max:99999.99',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,check,card',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'pilgrim_id.required' => 'Le pèlerin est obligatoire.',
            'pilgrim_id.exists' => 'Le pèlerin sélectionné n\'existe pas.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
            'amount.max' => 'Le montant ne peut pas dépasser 99 999,99.',
            'payment_date.required' => 'La date de paiement est obligatoire.',
            'payment_date.before_or_equal' => 'La date de paiement ne peut pas être dans le futur.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',
            'payment_method.in' => 'La méthode de paiement doit être: espèces, virement, chèque ou carte.',
            'reference.max' => 'La référence ne peut pas dépasser 255 caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
        ];
    }
}