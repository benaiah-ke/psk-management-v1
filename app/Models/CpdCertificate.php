<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpdCertificate extends Model
{
    protected $fillable = [
        'user_id', 'certificate_number', 'period_year',
        'total_points', 'required_points', 'file_path', 'issued_date',
    ];

    protected function casts(): array
    {
        return [
            'period_year' => 'integer',
            'total_points' => 'integer',
            'required_points' => 'integer',
            'issued_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
