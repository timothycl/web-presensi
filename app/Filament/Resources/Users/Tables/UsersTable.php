<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Models\User;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                    TextColumn::make('employee_id')
                    ->searchable(),
                    TextColumn::make('role')
                    ->badge(),
                TextColumn::make('approval_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()->isSuperAdmin()),
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
                    ->label('Filter Perusahaan')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                \Filament\Actions\Action::make('manage')
                    ->label('Manage')
                    ->view('filament.tables.actions.manage-selection')
                    ->hidden(fn (\Filament\Tables\Contracts\HasTable $livewire) => $livewire->activeTab === 'pending'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->hidden(fn (User $record): bool => $record->approval_status === 'pending'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\ForceDeleteBulkAction::make(),
                    \Filament\Actions\RestoreBulkAction::make(),
                ])->label('Delete Selected Users'),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $record): bool => 
                (auth()->user()->isSuperAdmin() || !$record->isSuperAdmin()) && $record->approval_status !== 'pending'
            );
    }
}
