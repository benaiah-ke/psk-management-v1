<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpdCertificate extends Model
{
    protected $fillable = [
        'user_id', 'year', 'total_points', 'certificate_number',
        'file_path', 'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'total_points' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
