<?php

namespace App\Filament\Resources\ShortLinks\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;



use Illuminate\Database\Eloquent\Model;

class ShortLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kısa URL (tam hali) - kopyalanabilir
                TextColumn::make('short_url')
                    ->label('Kısa URL')
                    ->state(fn(Model $record) => url('/' . $record->code))
                    ->copyable()
                    ->toggleable(),

                // Kod
                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                // Hedef URL
                TextColumn::make('destination_url')
                    ->label('Hedef URL')
                    ->limit(60)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Başlangıç')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expires_at')
                    ->label('Bitiş')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('clicks_count')
                    ->label('Tıklama')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Oluşturma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Silinme')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('Silinenler'),
            ])
            ->recordActions([
                ViewAction::make(),

                // Linki yeni sekmede aç (kısa link)
                Action::make('open_short')
                    ->label('Aç')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn(Model $record) => url('/' . $record->code), shouldOpenInNewTab: true),

                EditAction::make(),

                // Soft delete
                DeleteAction::make(),

                // Sadece trashed ise görünsün
                RestoreAction::make()
                    ->visible(fn(Model $record) => method_exists($record, 'trashed') && $record->trashed()),

                // Sadece trashed ise görünsün
                ForceDeleteAction::make()
                    ->visible(fn(Model $record) => method_exists($record, 'trashed') && $record->trashed()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
