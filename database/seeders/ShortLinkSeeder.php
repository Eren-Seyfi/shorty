<?php

namespace Database\Seeders;

use App\Models\ShortLink;
use Illuminate\Database\Seeder;

class ShortLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'code' => 'google',
                'destination_url' => 'https://www.google.com',
                'title' => 'Google',
                'is_active' => true,
                'starts_at' => null,
                'expires_at' => null,
                'clicks_count' => 0,
                'user_id' => null,
            ],
            [
                'code' => 'youtube',
                'destination_url' => 'https://www.youtube.com',
                'title' => 'YouTube',
                'is_active' => true,
                'starts_at' => null,
                'expires_at' => null,
                'clicks_count' => 0,
                'user_id' => null,
            ],
        ];

        foreach ($links as $data) {
            ShortLink::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}