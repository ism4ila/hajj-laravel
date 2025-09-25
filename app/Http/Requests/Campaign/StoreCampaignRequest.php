<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('campaign_create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:campaigns,name',
            'type' => 'required|in:hajj,omra',
            'year_hijri' => 'required|integer|min:1400|max:1500',
            'year_gregorian' => 'required|integer|min:1980|max:2100',
            'price' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la campagne est obligatoire.',
            'name.unique' => 'Une campagne avec ce nom existe déjà.',
            'type.required' => 'Le type de campagne est obligatoire.',
            'type.in' => 'Le type doit être "hajj" ou "omra".',
            'year_hijri.required' => 'L\'année Hijri est obligatoire.',
            'year_gregorian.required' => 'L\'année grégorienne est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
            'price.min' => 'Le prix doit être positif.',
            'departure_date.required' => 'La date de départ est obligatoire.',
            'departure_date.after' => 'La date de départ doit être dans le futur.',
            'return_date.required' => 'La date de retour est obligatoire.',
            'return_date.after' => 'La date de retour doit être après la date de départ.',
        ];
    }
}