<?php

namespace App\Filament\Resources\ShortLinks\Schemas;

use App\Models\ShortLink;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShortLinkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                /*   TextEntry::make('code'),
                  TextEntry::make('destination_url')
                      ->columnSpanFull(),
                  TextEntry::make('title')
                      ->placeholder('-'),
                  IconEntry::make('is_active')
                      ->boolean(),
                  TextEntry::make('starts_at')
                      ->dateTime()
                      ->placeholder('-'),
                  TextEntry::make('expires_at')
                      ->dateTime()
                      ->placeholder('-'),
                  TextEntry::make('clicks_count')
                      ->numeric(),
                  TextEntry::make('user.name')
                      ->label('User')
                      ->placeholder('-'),
                  TextEntry::make('created_at')
                      ->dateTime()
                      ->placeholder('-'),
                  TextEntry::make('updated_at')
                      ->dateTime()
                      ->placeholder('-'),
                  TextEntry::make('deleted_at')
                      ->dateTime()
                      ->visible(fn (ShortLink $record): bool => $record->trashed()), */
            ]);
    }
}
