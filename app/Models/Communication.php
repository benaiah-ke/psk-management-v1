<?php

namespace App\Models;

use App\Enums\CommunicationType;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
        'sent_by', 'type', 'subject', 'body', 'recipient_count',
        'sent_count', 'failed_count', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => CommunicationType::class,
            'recipient_count' => 'integer',
            'sent_count' => 'integer',
            'failed_count' => 'integer',
            'sent_at' => 'datetime',
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
