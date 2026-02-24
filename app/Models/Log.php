<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\PersonalAccessToken;

class Log extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
        'user_id',
        'api_token_id',
        'ip_address',
        'method',
        'route',
        'status_code',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(
            PersonalAccessToken::class,
            'api_token_id'
        );
    }
}
