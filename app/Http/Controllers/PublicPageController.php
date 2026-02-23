<?php

namespace App\Http\Controllers;

use App\Enums\PageKey;
use App\Models\Page;
use App\Models\ShortLink;
use App\Models\ShortLinkClick;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class PublicPageController extends Controller
{
      public function home()
      {
            return $this->render(PageKey::Home, Response::HTTP_OK);
      }

      public function status(string $reason)
      {
            [$key, $status, $badge] = match ($reason) {
                  'disabled' => [PageKey::LinkInactive, Response::HTTP_GONE, 'PASİF'],
                  'expired' => [PageKey::LinkExpired, Response::HTTP_GONE, 'SÜRESİ DOLDU'],
                  'not-started' => [PageKey::LinkInactive, Response::HTTP_FORBIDDEN, 'AKTİF DEĞİL'],
                  'not-found' => [PageKey::LinkNotFound, Response::HTTP_NOT_FOUND, '404'],
                  default => [PageKey::LinkNotFound, Response::HTTP_NOT_FOUND, '404'],
            };

            return $this->render($key, $status, $badge);
      }

      public function redirect(Request $request, string $code)
      {
            $short = ShortLink::query()
                  ->where('code', $code)
                  ->first();

            if (!$short) {
                  return redirect()->route('status', ['reason' => 'not-found']);
            }

            // Sonucu belirle (expired/disabled/not-started olsa bile click log tutulacak)
            $result = 'redirect';
            $reason = null;

            if (!$short->is_active) {
                  $result = 'disabled';
                  $reason = 'disabled';
            } elseif ($short->starts_at?->isFuture()) {
                  $result = 'not_started';
                  $reason = 'not-started';
            } elseif ($short->expires_at?->isPast()) {
                  $result = 'expired';
                  $reason = 'expired';
            }

            // User-Agent parse (cihaz/tarayıcı/OS)
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent() ?? '');

            $deviceType = $agent->isRobot()
                  ? 'bot'
                  : ($agent->isTablet()
                        ? 'tablet'
                        : ($agent->isMobile() ? 'mobile' : 'desktop'));

            // Click log (detay kayıt) -> HER DURUMDA
            ShortLinkClick::create([
                  'short_link_id' => $short->id,
                  'user_id' => $request->user()?->id,
                  'result' => $result,

                  'ip' => $request->ip(),
                  'user_agent' => $request->userAgent(),
                  'referer' => $request->headers->get('referer'),
                  'clicked_at' => now(),

                  'device_type' => $deviceType,
                  'browser' => $agent->browser() ?: null,
                  'os' => $agent->platform() ?: null,
            ]);

            // Toplam sayaç -> sadece başarılı redirect için
            if ($result === 'redirect') {
                  $short->increment('clicks_count');
                  return redirect()->away($short->destination_url, 302);
            }

            return redirect()->route('status', ['reason' => $reason]);
      }

      private function render(PageKey $key, int $status, ?string $badge = null)
      {
            $page = Page::query()
                  ->where('key', $key->value)
                  ->where('is_active', true)
                  ->first();

            if (!$page) {
                  abort(Response::HTTP_NOT_FOUND);
            }

            $html = RichContentRenderer::make($page->content)->toHtml();

            return response()->view('pages.home', [
                  'title' => $page->title ?? $key->label(),
                  'html' => $html,
                  'badge' => $badge,
            ], $status);
      }
}