<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationRecipient extends Model
{
    protected $fillable = [
        'communication_id', 'user_id', 'email', 'phone',
        'status', 'sent_at', 'delivered_at', 'failed_at', 'failure_reason',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function communication()
    {
        return $this->belongsTo(Communication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
