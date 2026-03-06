<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'ticket_type_id', 'registration_number',
        'status', 'amount_paid', 'invoice_id', 'qr_code_data',
        'checked_in_at', 'checked_in_by', 'dietary_requirements', 'special_needs', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => RegistrationStatus::class,
            'amount_paid' => 'decimal:2',
            'checked_in_at' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(EventTicketType::class, 'ticket_type_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function isCheckedIn(): bool
    {
        return $this->checked_in_at !== null;
    }
}
