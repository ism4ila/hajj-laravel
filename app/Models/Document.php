<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'pilgrim_id',
        'cni',
        'cni_file',
        'passport',
        'passport_file',
        'visa',
        'visa_file',
        'vaccination_certificate',
        'vaccination_file',
        'photo_file',
        'documents_complete',
    ];

    protected function casts(): array
    {
        return [
            'documents_complete' => 'boolean',
        ];
    }

    public function pilgrim()
    {
        return $this->belongsTo(Pilgrim::class);
    }

    public function checkCompleteness()
    {
        $required = ['cni', 'passport', 'photo_file'];
        $complete = true;

        foreach ($required as $field) {
            if (empty($this->$field)) {
                $complete = false;
                break;
            }
        }

        $this->documents_complete = $complete;
        $this->save();

        return $complete;
    }

    public function getMissingDocumentsAttribute()
    {
        $documents = [
            'cni' => 'Carte d\'identité nationale',
            'passport' => 'Passeport',
            'visa' => 'Visa',
            'vaccination_certificate' => 'Certificat de vaccination',
            'photo_file' => 'Photo d\'identité',
        ];

        $missing = [];
        foreach ($documents as $field => $name) {
            if (empty($this->$field)) {
                $missing[] = $name;
            }
        }

        return $missing;
    }
}
