<?php

namespace App\Filament\Resources\ShortLinks\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static ?string $title = 'Tıklamalar';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('clicked_at', 'desc')
            ->columns([
                TextColumn::make('clicked_at')
                    ->label('Tarih / Saat')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),

                BadgeColumn::make('result')
                    ->label('Sonuç')
                    ->formatStateUsing(fn(?string $state) => match ($state) {
                        'redirect' => 'Yönlendirildi',
                        'disabled' => 'Pasif',
                        'expired' => 'Süresi doldu',
                        'not_started' => 'Henüz başlamadı',
                        default => $state ?? '-',
                    })
                    ->colors([
                        'success' => 'redirect',
                        'danger' => 'disabled',
                        'warning' => 'expired',
                        'gray' => 'not_started',
                    ])
                    ->sortable(),

                TextColumn::make('ip')
                    ->label('IP')
                    ->copyable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('device_type')
                    ->label('Cihaz')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('browser')
                    ->label('Tarayıcı')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('os')
                    ->label('OS')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('referer')
                    ->label('Referer')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->referer)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user_agent')
                    ->label('User-Agent')
                    ->limit(60)
                    ->tooltip(fn($record) => $record->user_agent)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->label('Sonuç')
                    ->options([
                        'redirect' => 'Yönlendirildi',
                        'disabled' => 'Pasif',
                        'expired' => 'Süresi doldu',
                        'not_started' => 'Henüz başlamadı',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Logları genelde silmek istemezsin, istersen aç:
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}