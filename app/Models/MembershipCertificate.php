<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipCertificate extends Model
{
    protected $fillable = [
        'membership_id', 'certificate_number', 'certificate_type',
        'issued_date', 'expiry_date', 'file_path', 'qr_code_data', 'generated_by',
    ];

    protected function casts(): array
    {
        return ['issued_date' => 'date', 'expiry_date' => 'date'];
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
