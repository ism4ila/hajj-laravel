<?php

namespace App\Http\Requests\Pilgrim;

use Illuminate\Foundation\Http\FormRequest;

class StorePilgrimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('pilgrim_create');
    }

    public function rules(): array
    {
        return [
            'campaign_id' => 'required|exists:campaigns,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today|after:1900-01-01',
            'phone' => 'required|string|max:20|unique:pilgrims,phone',
            'email' => 'nullable|email|max:255|unique:pilgrims,email',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'campaign_id.required' => 'La campagne est obligatoire.',
            'campaign_id.exists' => 'La campagne sélectionnée n\'existe pas.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom est obligatoire.',
            'gender.required' => 'Le genre est obligatoire.',
            'gender.in' => 'Le genre doit être "male" ou "female".',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'date_of_birth.after' => 'La date de naissance doit être après 1900.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'address.required' => 'L\'adresse est obligatoire.',
            'emergency_contact.required' => 'Le contact d\'urgence est obligatoire.',
            'emergency_phone.required' => 'Le téléphone d\'urgence est obligatoire.',
        ];
    }
}