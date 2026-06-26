<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('attendance_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('check_in_time')
                    ->time('H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('check_in_latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('check_in_longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('check_in_photo')
                    ->label('Foto Masuk')
                    ->circular()
                    ->action(
                        \Filament\Actions\Action::make('view_check_in_photo')
                            ->modalHeading('Foto Masuk')
                            ->modalContent(fn ($record) => $record->check_in_photo ? new \Illuminate\Support\HtmlString('<img src="' . \Illuminate\Support\Facades\Storage::disk('public')->url($record->check_in_photo) . '" style="width: 100%; border-radius: 0.5rem;" />') : null)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('check_out_time')
                    ->time('H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('check_out_latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('check_out_longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('check_out_photo')
                    ->label('Foto Keluar')
                    ->circular()
                    ->action(
                        \Filament\Actions\Action::make('view_check_out_photo')
                            ->modalHeading('Foto Keluar')
                            ->modalContent(fn ($record) => $record->check_out_photo ? new \Illuminate\Support\HtmlString('<img src="' . \Illuminate\Support\Facades\Storage::disk('public')->url($record->check_out_photo) . '" style="width: 100%; border-radius: 0.5rem;" />') : null)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                \Filament\Tables\Filters\SelectFilter::make('company_id')
                    ->label('Perusahaan')
                    ->options(\App\Models\Company::pluck('name', 'id'))
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($data) {
                                $query->where('company_id', $data['value']);
                            });
                        }
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
