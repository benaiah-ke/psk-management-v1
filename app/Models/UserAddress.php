<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'type', 'address_1', 'address_2',
        'city', 'county', 'postal_code', 'country', 'is_primary',
    ];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
