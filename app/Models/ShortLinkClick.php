<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortLinkClick extends Model
{
    public $timestamps = false; // clicked_at var, created_at/updated_at yok

    protected $fillable = [
        'short_link_id',
        'user_id',

        'result',

        'ip',
        'user_agent',
        'referer',

        'device_type',
        'browser',
        'os',

        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}