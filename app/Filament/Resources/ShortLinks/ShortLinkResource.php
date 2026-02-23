<?php

namespace App\Filament\Resources\ShortLinks;

use App\Filament\Resources\ShortLinks\Pages\CreateShortLink;
use App\Filament\Resources\ShortLinks\Pages\EditShortLink;
use App\Filament\Resources\ShortLinks\Pages\ListShortLinks;
use App\Filament\Resources\ShortLinks\Pages\ViewShortLink;
use App\Filament\Resources\ShortLinks\RelationManagers\ClicksRelationManager;
use App\Filament\Resources\ShortLinks\Schemas\ShortLinkForm;
use App\Filament\Resources\ShortLinks\Schemas\ShortLinkInfolist;
use App\Filament\Resources\ShortLinks\Tables\ShortLinksTable;
use App\Filament\Resources\ShortLinks\Widgets\ShortLinkClicksChart;
use App\Models\ShortLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShortLinkResource extends Resource
{
    protected static ?string $model = ShortLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Link;

    public static function form(Schema $schema): Schema
    {
        return ShortLinkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShortLinkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortLinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class,
        ];
    }

    // ✅ Widget register burada
    public static function getWidgets(): array
    {
        return [
            ShortLinkClicksChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShortLinks::route('/'),
            'create' => CreateShortLink::route('/create'),
            'view' => ViewShortLink::route('/{record}'),
            'edit' => EditShortLink::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}