<?php

namespace App\Filament\Resources\ShortLinks\Pages;

use App\Filament\Resources\ShortLinks\ShortLinkResource;
use App\Services\ShortCodeGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateShortLink extends CreateRecord
{
    protected static string $resource = ShortLinkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // kullanıcı yazdıysa trimle
        $data['code'] = isset($data['code']) ? trim((string) $data['code']) : null;

        if (empty($data['code'])) {
            $data['code'] = app(ShortCodeGenerator::class)->generate();
        } else {
            // istersen kodu normalize et (opsiyonel)
            $data['code'] = Str::lower($data['code']);
        }

        // kullanıcıyı otomatik bağla (login yoksa null kalır)
        $data['user_id'] = $data['user_id'] ?? auth()->id();

        return $data;
    }
}
