<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;

class RedirectShortLinkController extends Controller
{
    public function __invoke(Request $request, string $code)
    {
        $short = ShortLink::query()
            ->where('code', $code)
            ->first();

        // Bulunamadıysa (soft deleted dahil) 404 sayfası kalsın
        if (!$short) {
            abort(404);
        }

        // Pasifse -> özel sayfa
        if (!$short->is_active) {
            return redirect()->route('status', ['reason' => 'disabled']);
        }

        // Henüz başlamadı -> özel sayfa
        if ($short->starts_at && $short->starts_at->isFuture()) {
            return redirect()->route('status', ['reason' => 'not-started']);
        }

        // Süresi doldu -> özel sayfa
        if ($short->expires_at && $short->expires_at->isPast()) {
            return redirect()->route('status', ['reason' => 'expired']);
        }

        $short->increment('clicks_count');

        return redirect()->away($short->destination_url, 302);
    }
}
