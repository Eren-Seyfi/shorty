<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortLink extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'destination_url',
        'title',
        'is_active',
        'starts_at',
        'expires_at',
        'clicks_count',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'clicks_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Link şu anda yayında mı?
     * - is_active false ise kapalı
     * - starts_at gelecekteyse henüz yayında değil
     * - expires_at geçmişteyse süresi dolmuş
     */

    public function isLive(?Carbon $now = null): bool
    {
        $now ??= now();

        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Kısa linkin tam URL'si.
     */
    public function shortUrl(): string
    {
        return url('/' . $this->code);
    }

}
