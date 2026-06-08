<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UpcomingEventResource\Pages;
use App\Models\UpcomingEvent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class UpcomingEventResource extends Resource
{
    protected static ?string $model = UpcomingEvent::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Upcoming Events';

    protected static ?string $modelLabel = 'Announcement';

    protected static ?string $slug = 'upcoming-events';

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. John\'s Birthday'),
                DatePicker::make('event_date')
                    ->required()
                    ->native(false)
                    ->displayFormat('d F Y')
                    ->closeOnDateSelection(),
                TextInput::make('label')
                    ->required()
                    ->placeholder('e.g. Today, Tomorrow, Upcoming'),
                Select::make('icon')
                    ->required()
                    ->options([
                        'heroicon-o-cake' => 'Cake',
                        'heroicon-o-user' => 'User',
                        'heroicon-o-flag' => 'Flag',
                        'heroicon-o-calendar' => 'Calendar',
                        'heroicon-o-bell' => 'Bell',
                        'heroicon-o-star' => 'Star',
                        'heroicon-o-academic-cap' => 'Academic Cap',
                        'heroicon-o-gift' => 'Gift',
                        'heroicon-o-check-badge' => 'Badge',
                        'heroicon-o-exclamation-triangle' => 'Warning',
                    ])
                    ->default('heroicon-o-calendar')
                    ->searchable(),
                ColorPicker::make('accent_color')
                    ->required()
                    ->default('#f59e0b'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event_date')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('label')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('icon'),
                ColorColumn::make('accent_color'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUpcomingEvents::route('/'),
            'create' => Pages\CreateUpcomingEvent::route('/create'),
            'edit' => Pages\EditUpcomingEvent::route('/{record}/edit'),
        ];
    }
}
