<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Enums\PageKey;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('key', 'asc')
            ->columns([
                TextColumn::make('key')
                    ->label('Sayfa (Key)')
                    ->formatStateUsing(function ($state): string {
                        // $state string olabilir veya model cast’inden enum gelebilir
                        $value = is_string($state) ? $state : ($state?->value ?? '');
                        $label = PageKey::tryFrom($value)?->label();

                        return $label ? "{$label} ({$value})" : (string) $value;
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn($record) => (string) ($record->title ?? ''))
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Durum')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Son Güncelleme')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Yayın Durumu')
                    ->placeholder('Tümü')
                    ->trueLabel('Yayında')
                    ->falseLabel('Kapalı'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}