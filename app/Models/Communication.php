<?php

namespace App\Models;

use App\Enums\CommunicationType;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
        'type', 'channel', 'subject', 'body', 'status',
        'sent_by', 'scheduled_at', 'sent_at', 'error_message', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'type' => CommunicationType::class,
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function recipients()
    {
        return $this->hasMany(CommunicationRecipient::class);
    }
}
