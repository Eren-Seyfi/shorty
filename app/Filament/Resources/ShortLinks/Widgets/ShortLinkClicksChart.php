<?php

namespace App\Filament\Resources\ShortLinks\Widgets;

use App\Models\ShortLink;
use App\Models\ShortLinkClick;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ShortLinkClicksChart extends ChartWidget
{
    public ?ShortLink $record = null;
    protected int|string|array $columnSpan = 'full';
    protected ?string $heading = 'Tıklama Analitiği (Detaylı)';

    public ?string $filter = '30d';

    protected function getFilters(): ?array
    {
        return [
            // Günlük
            '7d' => 'Günlük • Son 7 gün',
            '30d' => 'Günlük • Son 30 gün',
            '90d' => 'Günlük • Son 90 gün',

            // Haftalık
            '12w' => 'Haftalık • Son 12 hafta',
            '52w' => 'Haftalık • Son 52 hafta',

            // Aylık
            '12m' => 'Aylık • Son 12 ay',
            '24m' => 'Aylık • Son 24 ay',

            // Yıllık
            '5y' => 'Yıllık • Son 5 yıl',
        ];
    }

    protected function getData(): array
    {
        $shortLinkId = $this->record?->id;

        if (!$shortLinkId) {
            return ['datasets' => [], 'labels' => []];
        }

        return match ($this->filter) {
            '7d' => $this->daily($shortLinkId, 7),
            '30d' => $this->daily($shortLinkId, 30),
            '90d' => $this->daily($shortLinkId, 90),

            '12w' => $this->weekly($shortLinkId, 12),
            '52w' => $this->weekly($shortLinkId, 52),

            '12m' => $this->monthly($shortLinkId, 12),
            '24m' => $this->monthly($shortLinkId, 24),

            '5y' => $this->yearly($shortLinkId, 5),

            default => $this->daily($shortLinkId, 30),
        };
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Tooltip'ta aynı noktada tüm dataset'leri görebilmek için.
     */
    protected function getOptions(): array
    {
        return [
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.25,
                ],
                'point' => [
                    'radius' => 2,
                    'hoverRadius' => 4,
                ],
            ],
        ];
    }

    private function daily(int $shortLinkId, int $days): array
    {
        $start = now()->startOfDay()->subDays($days - 1);

        $rows = ShortLinkClick::query()
            ->selectRaw('DATE(clicked_at) as k')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(result = 'redirect') as redirect")
            ->selectRaw("SUM(result = 'expired') as expired")
            ->selectRaw("SUM(result = 'disabled') as disabled")
            ->selectRaw("SUM(result = 'not_started') as not_started")
            ->selectRaw("COUNT(DISTINCT ip) as uniq_ip")
            ->selectRaw("SUM(device_type = 'mobile') as mobile")
            ->selectRaw("SUM(device_type = 'desktop') as desktop")
            ->selectRaw("SUM(device_type = 'tablet') as tablet")
            ->selectRaw("SUM(device_type = 'bot') as bot")
            ->selectRaw("COUNT(DISTINCT os) as uniq_os")
            ->selectRaw("COUNT(DISTINCT browser) as uniq_browser")
            ->where('short_link_id', $shortLinkId)
            ->where('clicked_at', '>=', $start)
            ->groupBy('k')
            ->orderBy('k')
            ->get()
            ->keyBy('k');

        $labels = [];
        $series = $this->emptySeries();

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d.m');

            $row = $rows->get($date);

            $this->pushRow($series, $row);
        }

        return $this->buildChartData($labels, $series);
    }

    private function weekly(int $shortLinkId, int $weeks): array
    {
        $start = now()->startOfWeek()->subWeeks($weeks - 1);

        // YEARWEEK(..., 3) -> ISO week formatı (MySQL)
        $rows = ShortLinkClick::query()
            ->selectRaw('YEARWEEK(clicked_at, 3) as k')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(result = 'redirect') as redirect")
            ->selectRaw("SUM(result = 'expired') as expired")
            ->selectRaw("SUM(result = 'disabled') as disabled")
            ->selectRaw("SUM(result = 'not_started') as not_started")
            ->selectRaw("COUNT(DISTINCT ip) as uniq_ip")
            ->selectRaw("SUM(device_type = 'mobile') as mobile")
            ->selectRaw("SUM(device_type = 'desktop') as desktop")
            ->selectRaw("SUM(device_type = 'tablet') as tablet")
            ->selectRaw("SUM(device_type = 'bot') as bot")
            ->selectRaw("COUNT(DISTINCT os) as uniq_os")
            ->selectRaw("COUNT(DISTINCT browser) as uniq_browser")
            ->where('short_link_id', $shortLinkId)
            ->where('clicked_at', '>=', $start)
            ->groupBy('k')
            ->orderBy('k')
            ->get()
            ->keyBy('k');

        $labels = [];
        $series = $this->emptySeries();

        for ($i = 0; $i < $weeks; $i++) {
            $weekStart = $start->copy()->addWeeks($i);

            $isoYear = (int) $weekStart->format('o');
            $isoWeek = (int) $weekStart->format('W');
            $key = (int) sprintf('%d%02d', $isoYear, $isoWeek); // 202501 gibi

            $labels[] = $weekStart->format('d.m');

            $row = $rows->get($key);

            $this->pushRow($series, $row);
        }

        return $this->buildChartData($labels, $series);
    }

    private function monthly(int $shortLinkId, int $months): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $rows = ShortLinkClick::query()
            ->selectRaw("DATE_FORMAT(clicked_at, '%Y-%m') as k")
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(result = 'redirect') as redirect")
            ->selectRaw("SUM(result = 'expired') as expired")
            ->selectRaw("SUM(result = 'disabled') as disabled")
            ->selectRaw("SUM(result = 'not_started') as not_started")
            ->selectRaw("COUNT(DISTINCT ip) as uniq_ip")
            ->selectRaw("SUM(device_type = 'mobile') as mobile")
            ->selectRaw("SUM(device_type = 'desktop') as desktop")
            ->selectRaw("SUM(device_type = 'tablet') as tablet")
            ->selectRaw("SUM(device_type = 'bot') as bot")
            ->selectRaw("COUNT(DISTINCT os) as uniq_os")
            ->selectRaw("COUNT(DISTINCT browser) as uniq_browser")
            ->where('short_link_id', $shortLinkId)
            ->where('clicked_at', '>=', $start)
            ->groupBy('k')
            ->orderBy('k')
            ->get()
            ->keyBy('k');

        $labels = [];
        $series = $this->emptySeries();

        for ($i = 0; $i < $months; $i++) {
            $m = $start->copy()->addMonths($i);
            $key = $m->format('Y-m');

            $labels[] = $m->format('m.Y');

            $row = $rows->get($key);

            $this->pushRow($series, $row);
        }

        return $this->buildChartData($labels, $series);
    }

    private function yearly(int $shortLinkId, int $years): array
    {
        $start = now()->startOfYear()->subYears($years - 1);

        $rows = ShortLinkClick::query()
            ->selectRaw('YEAR(clicked_at) as k')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(result = 'redirect') as redirect")
            ->selectRaw("SUM(result = 'expired') as expired")
            ->selectRaw("SUM(result = 'disabled') as disabled")
            ->selectRaw("SUM(result = 'not_started') as not_started")
            ->selectRaw("COUNT(DISTINCT ip) as uniq_ip")
            ->selectRaw("SUM(device_type = 'mobile') as mobile")
            ->selectRaw("SUM(device_type = 'desktop') as desktop")
            ->selectRaw("SUM(device_type = 'tablet') as tablet")
            ->selectRaw("SUM(device_type = 'bot') as bot")
            ->selectRaw("COUNT(DISTINCT os) as uniq_os")
            ->selectRaw("COUNT(DISTINCT browser) as uniq_browser")
            ->where('short_link_id', $shortLinkId)
            ->where('clicked_at', '>=', $start)
            ->groupBy('k')
            ->orderBy('k')
            ->get()
            ->keyBy('k');

        $labels = [];
        $series = $this->emptySeries();

        for ($i = 0; $i < $years; $i++) {
            $y = $start->copy()->addYears($i);
            $key = (int) $y->format('Y');

            $labels[] = (string) $key;

            $row = $rows->get($key);

            $this->pushRow($series, $row);
        }

        return $this->buildChartData($labels, $series);
    }

    /**
     * Tüm metrikler için boş seri seti.
     */
    private function emptySeries(): array
    {
        return [
            'total' => [],
            'redirect' => [],
            'expired' => [],
            'disabled' => [],
            'not_started' => [],
            'uniq_ip' => [],
            'mobile' => [],
            'desktop' => [],
            'tablet' => [],
            'bot' => [],
            'uniq_os' => [],
            'uniq_browser' => [],
        ];
    }

    /**
     * Row'u serilere push eder (row yoksa 0 basar).
     */
    private function pushRow(array &$series, $row): void
    {
        $series['total'][] = (int) ($row->total ?? 0);
        $series['redirect'][] = (int) ($row->redirect ?? 0);
        $series['expired'][] = (int) ($row->expired ?? 0);
        $series['disabled'][] = (int) ($row->disabled ?? 0);
        $series['not_started'][] = (int) ($row->not_started ?? 0);
        $series['uniq_ip'][] = (int) ($row->uniq_ip ?? 0);
        $series['mobile'][] = (int) ($row->mobile ?? 0);
        $series['desktop'][] = (int) ($row->desktop ?? 0);
        $series['tablet'][] = (int) ($row->tablet ?? 0);
        $series['bot'][] = (int) ($row->bot ?? 0);
        $series['uniq_os'][] = (int) ($row->uniq_os ?? 0);
        $series['uniq_browser'][] = (int) ($row->uniq_browser ?? 0);
    }

    /**
     * Chart.js datasets yapısını döner.
     * Hover'da hepsi gözüksün diye hepsini dataset yapıyoruz.
     */
    private function buildChartData(array $labels, array $series): array
    {
        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Toplam', 'data' => $series['total']],

                ['label' => 'Redirect', 'data' => $series['redirect']],
                ['label' => 'Expired', 'data' => $series['expired']],
                ['label' => 'Disabled', 'data' => $series['disabled']],
                ['label' => 'Not started', 'data' => $series['not_started']],

                ['label' => 'Benzersiz IP', 'data' => $series['uniq_ip']],
                ['label' => 'Benzersiz OS', 'data' => $series['uniq_os']],
                ['label' => 'Benzersiz Browser', 'data' => $series['uniq_browser']],

                ['label' => 'Mobile', 'data' => $series['mobile']],
                ['label' => 'Desktop', 'data' => $series['desktop']],
                ['label' => 'Tablet', 'data' => $series['tablet']],
                ['label' => 'Bot', 'data' => $series['bot']],
            ],
        ];
    }
}