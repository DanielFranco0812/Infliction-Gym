<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipPayment extends Model
{
    protected $fillable = [
        'user_id',
        'plan',
        'credits',
        'amount',
        'reference_number',
        'checkout_session_id',
        'webhook_event_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}