<?php

namespace App\Filament\Resources\ShortLinks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ShortLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kısa Link')
                ->columnSpanFull()
                ->components([
                    Section::make('Link Bilgileri')
                        ->columnSpanFull()
                        ->components([
                            Callout::make('Bilgi')
                                ->icon(Heroicon::InformationCircle)
                                ->description(
                                    'Kısa Kod alanını boş bırakırsan sistem otomatik olarak üretir.
Hedef URL tam adres olmalıdır (https:// ile başlamalıdır).
Linki dilediğin zaman pasif yapabilir veya yayın başlangıç / bitiş tarihi belirleyebilirsin.'
                                )
                                ->color('info')
                                ->columnSpanFull(),

                            TextInput::make('code')
                                ->label('Kısa Kod')
                                ->helperText('Boş bırakırsan otomatik üretilecek.')
                                ->placeholder('örn: aZ3k9pQ')
                                ->maxLength(32)
                                ->unique(ignoreRecord: true)
                                ->regex('/^[a-zA-Z0-9_-]+$/')
                                ->nullable()
                                ->columnSpanFull(),

                            TextInput::make('title')
                                ->label('Başlık')
                                ->maxLength(255)
                                ->nullable()
                                ->columnSpanFull(),

                            TextInput::make('destination_url')
                                ->label('Hedef URL')
                                ->required()
                                ->url()
                                ->maxLength(2048)
                                ->placeholder('https://ornek.com/sayfa')
                                ->suffixIcon(Heroicon::GlobeAlt)
                                ->helperText('Kullanıcı bu kısa linke tıkladığında buraya yönlendirilir.')
                                ->columnSpanFull(),

                            Toggle::make('is_active')
                                ->label('Aktif mi?')
                                ->default(true)
                                ->columnSpanFull(),
                        ]),

                    Section::make('Yayın Planı')
                        ->columnSpanFull()
                        ->components([
                            Callout::make('Yayın Planı')
                                ->icon(Heroicon::Clock)
                                ->description(
                                    'Başlangıç ve bitiş tarihleri isteğe bağlıdır.
Başlangıç tarihi gelecekteyse link o tarihe kadar çalışmaz.
Bitiş tarihi geçmişse link süresi dolmuş kabul edilir.'
                                )
                                ->color('warning')
                                ->columnSpanFull(),

                            DateTimePicker::make('starts_at')
                                ->label('Başlangıç')
                                ->seconds(false)
                                ->nullable()
                                ->columnSpanFull(),

                            DateTimePicker::make('expires_at')
                                ->label('Bitiş')
                                ->seconds(false)
                                ->nullable()
                                ->rule('after_or_equal:starts_at')
                                ->helperText('Başlangıç seçildiyse bitiş tarihi, başlangıçtan önce olamaz.')
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
