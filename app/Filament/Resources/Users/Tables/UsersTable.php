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
            ->poll('10s')
            ->columns([
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
            ])
            ->headerActions([
                \Filament\Actions\Action::make('manage')
                    ->label('Manage')
                    ->view('filament.tables.actions.manage-selection'),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (User $record) => $record->update(['approval_status' => 'approved']))
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->approval_status === 'pending' && auth()->user()->isAdmin()),
                \Filament\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (User $record) => $record->update(['approval_status' => 'rejected']))
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->approval_status === 'pending' && auth()->user()->isAdmin()),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    \Filament\Actions\RestoreBulkAction::make(),
                ])->label('Delete Selected Users'),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $record): bool => 
                auth()->user()->isSuperAdmin() || !$record->isSuperAdmin()
            );
    }
}
