<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;


class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pengajuan Cuti/Izin';

    protected static ?string $modelLabel = 'Pengajuan';

    protected static ?string $pluralModelLabel = 'Pengajuan';

    protected static ?string $slug = 'leaves';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        $isAdmin = auth()->user()?->isAdmin();

        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Karyawan')
                    ->default(auth()->id())
                    ->disabled()
                    ->dehydrated()
                    ->hidden(! $isAdmin),
                Select::make('type')
                    ->options([
                        'cuti' => 'Cuti',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ])
                    ->label('Tipe')
                    ->required()
                    ->disabled($isAdmin),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->disabled($isAdmin),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->disabled($isAdmin),
                Textarea::make('reason')
                    ->label('Alasan')
                    ->required()
                    ->disabled($isAdmin),
                FileUpload::make('document')
                    ->label('Dokumen / Surat Dokter')
                    ->directory('leaves')
                    ->downloadable()
                    ->openable()
                    ->disabled($isAdmin),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending (Menunggu)',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status')
                    ->default('pending')
                    ->required()
                    ->disabled(! $isAdmin)
                    ->hidden(fn (string $operation) => ! $isAdmin && $operation === 'create'),
                Textarea::make('admin_note')
                    ->label('Catatan Admin')
                    ->placeholder('Alasan ditolak atau catatan tambahan...')
                    ->disabled(! $isAdmin)
                    ->hidden(fn (string $operation) => ! $isAdmin && $operation === 'create'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        $isAdmin = auth()->user()?->isAdmin();

        return $table
            ->contentGrid($isAdmin ? [
                'md' => 2,
                'xl' => 3,
            ] : null)
            ->columns($isAdmin ? [
                \Filament\Tables\Columns\Layout\Stack::make([
                    \Filament\Tables\Columns\Layout\Split::make([
                        TextColumn::make('user.name')
                            ->weight('bold')
                            ->size('lg')
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'sakit' => 'danger',
                                'izin' => 'warning',
                                'cuti' => 'success',
                                default => 'gray',
                            }),
                    ]),
                    TextColumn::make('status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'gray',
                        }),
                    TextColumn::make('start_date')
                        ->formatStateUsing(fn ($record) => \Carbon\Carbon::parse($record->start_date)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($record->end_date)->format('d M Y'))
                        ->icon('heroicon-m-calendar'),
                    TextColumn::make('reason')
                        ->icon('heroicon-m-chat-bubble-left-ellipsis')
                        ->limit(50),
                ])->space(3),
            ] : [
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sakit' => 'danger',
                        'izin' => 'warning',
                        'cuti' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Setuju')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->visible(fn (Leave $record) => $isAdmin && $record->status === 'pending')
                    ->action(function (Leave $record, array $data) {
                        $record->update([
                            'status' => 'approved',
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);
                    })
                    ->form([
                        \Filament\Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin (Opsional)')
                            ->placeholder('Tambahkan catatan opsional jika disetujui...'),
                    ]),
                \Filament\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->button()
                    ->visible(fn (Leave $record) => $isAdmin && $record->status === 'pending')
                    ->action(function (Leave $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);
                    })
                    ->form([
                        \Filament\Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin (Opsional)')
                            ->placeholder('Alasan penolakan atau catatan tambahan...'),
                    ]),
                EditAction::make()
                    ->visible(fn (Leave $record) => $isAdmin || $record->status === 'pending'),
            ])
            ->toolbarActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLeaves::route('/'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
