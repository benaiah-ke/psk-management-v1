<?php

namespace App\Models;

use App\Enums\SponsorTier;
use Illuminate\Database\Eloquent\Model;

class EventSponsor extends Model
{
    protected $fillable = [
        'event_id', 'name', 'company', 'email', 'phone', 'tier',
        'amount', 'logo_path', 'website_url', 'description', 'is_confirmed', 'invoice_id',
    ];

    protected function casts(): array
    {
        return ['tier' => SponsorTier::class, 'amount' => 'decimal:2', 'is_confirmed' => 'boolean'];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
