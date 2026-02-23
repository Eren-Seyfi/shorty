<?php

namespace App\Http\Controllers;

use App\Enums\PageKey;
use App\Models\Page;
use App\Models\ShortLink;
use App\Models\ShortLinkClick;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
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

            [$result, $reason] = $this->resolveLinkResult($short);

            $this->logClick($request, $short->id, $result);

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

            $html = $this->normalizeHtml((string) $page->content);

            return response()->view('pages.home', [
                  'title' => $page->title ?? $key->label(),
                  'html' => new HtmlString($html),
                  'badge' => $badge,
            ], $status);
      }

      private function normalizeHtml(string $html): string
      {
            // storage:link yoksa eski url'leri düzelt
            $html = str_replace('src="/storage/pages/attachments/', 'src="/pages/attachments/', $html);
            $html = str_replace("src='/storage/pages/attachments/", "src='/pages/attachments/", $html);

            // relative src'leri absolute yap (status sayfalarında bozulmasın)
            $html = preg_replace('#src="(pages/attachments/)#', 'src="/$1', $html);
            $html = preg_replace("#src='(pages/attachments/)#", "src='/$1", $html);

            return $html;
      }

      private function resolveLinkResult(ShortLink $short): array
      {
            if (!$short->is_active) {
                  return ['disabled', 'disabled'];
            }

            if ($short->starts_at?->isFuture()) {
                  return ['not_started', 'not-started'];
            }

            if ($short->expires_at?->isPast()) {
                  return ['expired', 'expired'];
            }

            return ['redirect', null];
      }

      private function logClick(Request $request, int $shortLinkId, string $result): void
      {
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent() ?? '');

            $deviceType = $agent->isRobot()
                  ? 'bot'
                  : ($agent->isTablet()
                        ? 'tablet'
                        : ($agent->isMobile() ? 'mobile' : 'desktop'));

            ShortLinkClick::create([
                  'short_link_id' => $shortLinkId,
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
      }
}