<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
