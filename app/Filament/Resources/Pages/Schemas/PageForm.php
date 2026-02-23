<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageKey;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Callout::make('pages_manager_tip')
                ->heading('Sayfa Yönetimi')
                ->description('Bu bölüm; anasayfa ve sistem hata sayfalarının (kırık link, pasif link, süresi dolmuş link) içeriklerini yönetmek içindir. "Key" alanı sayfanın sistemdeki sabit kimliğidir.')
                ->info()
                ->columnSpanFull(),

            Section::make('Sayfa Bilgileri')
                ->description('Sayfanın kimliği (key), başlığı ve yayın durumu.')
                ->icon(Heroicon::DocumentText)
                ->columns([
                    'default' => 1,
                    'md' => 2,
                ])
                ->schema([
                    Callout::make('page_key_info')
                        ->heading('Key hakkında')
                        ->description('Key seçimi, sayfanın sistemde hangi ekrana karşılık geldiğini belirler. Örn: home, link_not_found, link_inactive, link_expired.')
                        ->info()
                        ->columnSpanFull(),

                    Select::make('key')
                        ->label('Sayfa (Key)')
                        ->options(PageKey::options())
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->helperText('Bu alan sabit kimliktir. Kayıt oluştuktan sonra değiştirmemen önerilir.')
                        ->unique(ignoreRecord: true)
                        ->disabled(fn($record) => filled($record))
                        ->dehydrated(),

                    TextInput::make('title')
                        ->label('Başlık')
                        ->maxLength(255)
                        ->nullable()
                        ->helperText('Frontend’de sayfa başlığı olarak kullanılabilir.'),

                    Toggle::make('is_active')
                        ->label('Yayında mı?')
                        ->required()
                        ->default(true)
                        ->helperText('Kapalıysa frontend bu sayfayı göstermeyebilir veya varsayılan içeriğe düşebilir.'),
                ])
                ->columnSpanFull(),

            Section::make('İçerik')
                ->description('Bu içerik frontend tarafında HTML olarak render edilir.')
                ->icon(Heroicon::PencilSquare)
                ->schema([
                    RichEditor::make('content')
                        ->label('Sayfa İçeriği')
                        ->columnSpanFull()
                        ->helperText('İpucu: Kırık link sayfasında kısa, net bir mesaj ve yönlendirme metni kullan.')

                        // ✅ Görsel / dosya yükleme ayarları
                        ->fileAttachmentsDisk('public') // ör: public | s3
                        ->fileAttachmentsDirectory('pages/attachments')
                        ->fileAttachmentsVisibility('public') // public | private

                        // ✅ Yüklenen görseller için kısıtlar
                        ->fileAttachmentsAcceptedFileTypes([
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                            'image/gif',
                        ])
                        ->fileAttachmentsMaxSize(5120) // KB => 5MB

                        // ✅ Editörde görsel boyutlandırma
                        ->resizableImages(),
                ])
                ->columnSpanFull(),
        ]);
    }
}