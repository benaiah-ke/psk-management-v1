<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipApplicationDocument extends Model
{
    protected $fillable = [
        'membership_application_id', 'document_type', 'file_path',
        'original_filename', 'file_size', 'mime_type', 'is_verified',
        'verified_by', 'verified_at', 'notes',
    ];

    protected function casts(): array
    {
        return ['is_verified' => 'boolean', 'verified_at' => 'datetime'];
    }

    public function application()
    {
        return $this->belongsTo(MembershipApplication::class, 'membership_application_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
