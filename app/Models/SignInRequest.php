<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignInRequest extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'ip_address',
        'user_agent',
        'approved',
        'approved_at',
        'approved_ip',
        'approved_user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
