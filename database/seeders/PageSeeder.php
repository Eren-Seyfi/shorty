<?php

namespace Database\Seeders;

use App\Enums\PageKey;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'key' => PageKey::Home->value,
                'title' => PageKey::Home->label(),
                'content' => '
                    <h1>Hoş geldin</h1>
                    <p>Bu sayfa içeriğini admin panelden (Filament) düzenleyebilirsin.</p>
                    <p>Örnek kısa linkler:</p>
                    <ul>
                        <li><a href="/google">/google</a></li>
                        <li><a href="/youtube">/youtube</a></li>
                    </ul>
                ',
                'is_active' => true,
            ],
            [
                'key' => PageKey::LinkNotFound->value,
                'title' => PageKey::LinkNotFound->label(),
                'content' => '
                    <h1>Link bulunamadı</h1>
                    <p>Bu kısa link geçersiz olabilir veya kaldırılmış olabilir.</p>
                    <p><a href="/">Ana sayfaya dön</a></p>
                ',
                'is_active' => true,
            ],
            [
                'key' => PageKey::LinkInactive->value,
                'title' => PageKey::LinkInactive->label(),
                'content' => '
                    <h1>Link pasif</h1>
                    <p>Bu kısa link şu anda devre dışı.</p>
                    <p><a href="/">Ana sayfaya dön</a></p>
                ',
                'is_active' => true,
            ],
            [
                'key' => PageKey::LinkExpired->value,
                'title' => PageKey::LinkExpired->label(),
                'content' => '
                    <h1>Linkin süresi dolmuş</h1>
                    <p>Bu kısa linkin kullanım süresi sona ermiş.</p>
                    <p><a href="/">Ana sayfaya dön</a></p>
                ',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}