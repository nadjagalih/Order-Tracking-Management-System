<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Pesanan';
    
    protected static ?string $modelLabel = 'Pesanan';
    
    protected static ?string $pluralModelLabel = 'Pesanan';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Client')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->label('Nama Client')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_phone')
                            ->label('No. Telepon Client')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('service_type')
                            ->label('Nama Projek')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Video Wedding, Photo Editing'),
                        Forms\Components\DateTimePicker::make('estimated_completion')
                            ->label('Estimasi Selesai')
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Pesanan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Internal')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Catatan ini hanya terlihat oleh admin'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Kode Order')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Nama Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_phone')
                    ->label('Telepon Client')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Nama Projek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'info' => 'in_progress',
                        'warning' => ['revision_1', 'revision_2'],
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'in_progress' => 'Dikerjakan',
                        'revision_1' => 'Revisi 1',
                        'revision_2' => 'Revisi 2',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('estimated_completion')
                    ->label('Estimasi')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'in_progress' => 'Dikerjakan',
                        'revision_1' => 'Revisi 1',
                        'revision_2' => 'Revisi 2',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('tracking')
                    ->label('Link Tracking')
                    ->icon('heroicon-o-link')
                    ->url(fn (Order $record): string => route('track.show', $record->order_code))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'draft' => 'Draft',
                                    'in_progress' => 'Dikerjakan',
                                    'revision_1' => 'Revisi 1',
                                    'revision_2' => 'Revisi 2',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->required()
                                ->native(false),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update(['status' => $data['status']]);
                                
                                $statusLabels = [
                                    'draft' => 'Draft',
                                    'in_progress' => 'Dikerjakan',
                                    'revision_1' => 'Revisi 1',
                                    'revision_2' => 'Revisi 2',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                                
                                $record->progress()->create([
                                    'title' => 'Status Diubah (Bulk): ' . $statusLabels[$data['status']],
                                    'description' => 'Status pesanan diubah secara massal menjadi "' . $statusLabels[$data['status']] . '"',
                                    'status' => $data['status'] === 'completed' ? 'success' : 'info',
                                    'created_by' => auth()->id(),
                                ]);
                            });
                            
                            Notification::make()
                                ->title('Status berhasil diupdate untuk ' . $records->count() . ' pesanan!')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_code')
                            ->label('Kode Order')
                            ->copyable()
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'secondary',
                                'in_progress' => 'info',
                                'revision_1' => 'warning',
                                'revision_2' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'draft' => 'Draft',
                                'in_progress' => 'Dikerjakan',
                                'revision_1' => 'Revisi 1',
                                'revision_2' => 'Revisi 2',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => $state,
                            })
                            ->suffixAction(
                                Action::make('change_status')
                                    ->label('Ubah Status')
                                    ->icon('heroicon-o-pencil-square')
                                    ->color('primary')
                                    ->form([
                                        Forms\Components\Select::make('status')
                                            ->label('Status Baru')
                                            ->options([
                                                'draft' => 'Draft',
                                                'in_progress' => 'Dikerjakan',
                                                'revision_1' => 'Revisi 1',
                                                'revision_2' => 'Revisi 2',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                            ])
                                            ->required()
                                            ->native(false),
                                        Forms\Components\Textarea::make('progress_description')
                                            ->label('Deskripsi Progress')
                                            ->placeholder('Jelaskan detail progress atau perubahan status...')
                                            ->required()
                                            ->rows(3),
                                    ])
                                    ->action(function (Order $record, array $data): void {
                                        $oldStatus = $record->status;
                                        $newStatus = $data['status'];
                                        
                                        // Update order status
                                        $record->update(['status' => $newStatus]);
                                        
                                        // Create progress entry
                                        $statusLabels = [
                                            'draft' => 'Draft',
                                            'in_progress' => 'Dikerjakan',
                                            'revision_1' => 'Revisi 1',
                                            'revision_2' => 'Revisi 2',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                        
                                        $record->progress()->create([
                                            'title' => 'Status Diubah: ' . $statusLabels[$newStatus],
                                            'description' => $data['progress_description'],
                                            'status' => $newStatus === 'completed' ? 'success' : 'info',
                                            'created_by' => auth()->id(),
                                        ]);
                                        
                                        Notification::make()
                                            ->title('Status berhasil diubah!')
                                            ->success()
                                            ->send();
                                    })
                            ),
                        Infolists\Components\TextEntry::make('client_name')
                            ->label('Nama Client'),
                        Infolists\Components\TextEntry::make('client_phone')
                            ->label('No. Telepon Client')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('service_type')
                            ->label('Nama Projek'),
                        Infolists\Components\TextEntry::make('estimated_completion')
                            ->label('Estimasi Selesai')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Catatan Internal')
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->headerActions([
                        Action::make('copy_tracking_link')
                            ->label('Copy Link Tracking')
                            ->icon('heroicon-o-link')
                            ->color('info')
                            ->form(fn (Order $record) => [
                                \Filament\Forms\Components\TextInput::make('tracking_link')
                                    ->label('Link Tracking Pesanan')
                                    ->default(url('/track/' . $record->order_code))
                                    ->readOnly()
                                    ->suffixAction(
                                        \Filament\Forms\Components\Actions\Action::make('copy')
                                            ->icon('heroicon-o-clipboard')
                                            ->action(function ($state) {
                                                Notification::make()
                                                    ->title('Link disalin ke clipboard!')
                                                    ->success()
                                                    ->send();
                                            })
                                            ->extraAttributes([
                                                'x-on:click' => 'navigator.clipboard.writeText($wire.data.tracking_link)',
                                            ])
                                    )
                                    ->helperText('Klik ikon clipboard untuk menyalin link')
                                    ->columnSpanFull(),
                            ])
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->modalHeading(fn (Order $record) => 'Link Tracking - ' . $record->order_code)
                            ->modalWidth('lg'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProgressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'in_progress')->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
