<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('campaign_update');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('campaigns', 'name')->ignore($this->route('campaign'))
            ],
            'type' => 'sometimes|required|in:hajj,omra',
            'year_hijri' => 'sometimes|required|integer|min:1400|max:1500',
            'year_gregorian' => 'sometimes|required|integer|min:1980|max:2100',
            'price' => 'sometimes|required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'departure_date' => 'sometimes|required|date',
            'return_date' => 'sometimes|required|date|after:departure_date',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:draft,active,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Une campagne avec ce nom existe déjà.',
            'type.in' => 'Le type doit être "hajj" ou "omra".',
            'price.min' => 'Le prix doit être positif.',
            'return_date.after' => 'La date de retour doit être après la date de départ.',
        ];
    }
}