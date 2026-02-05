<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'progress';
    
    protected static ?string $title = 'Timeline Progress';
    
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('title')
                    ->label('Judul Progress')
                    ->options([
                        'Pesanan Dibuat' => 'Pesanan Dibuat',
                        'Sedang Diproses' => 'Sedang Diproses',
                        'Dalam Pengerjaan' => 'Dalam Pengerjaan',
                        'Menunggu Review' => 'Menunggu Review',
                        'Revisi Diperlukan' => 'Revisi Diperlukan',
                        'Hampir Selesai' => 'Hampir Selesai',
                        'Selesai Dikerjakan' => 'Selesai Dikerjakan',
                        'Siap Diambil' => 'Siap Diambil',
                    ])
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->createOptionForm([
                        Forms\Components\TextInput::make('custom_title')
                            ->label('Judul Custom')
                            ->required(),
                    ])
                    ->createOptionUsing(function ($data) {
                        return $data['custom_title'];
                    }),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(3),
                Forms\Components\Repeater::make('files')
                    ->label('Link File Hasil Pekerjaan')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Link File')
                            ->url()
                            ->required()
                            ->placeholder('https://drive.google.com/... atau link lainnya')
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama File')
                            ->placeholder('Contoh: Video Final.mp4')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Link File')
                    ->helperText('Masukkan link file hasil pekerjaan dari Google Drive, Dropbox, atau cloud storage lainnya')
                    ->collapsible(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->description(function ($record) {
                        if (!$record->files) return null;
                        
                        $files = is_string($record->files) ? json_decode($record->files, true) : $record->files;
                        if (!$files || !is_array($files)) return null;
                        
                        $count = count($files);
                        return $count . ' file dilampirkan';
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('quick_progress')
                    ->label('Tambah Progress & Update Status')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('new_status')
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
                            ->native(false)
                            ->live()
                            ->helperText('Progress akan otomatis diberi judul berdasarkan status'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Progress')
                            ->placeholder('Jelaskan detail progress atau perubahan status...')
                            ->required()
                            ->rows(3),
                        Forms\Components\Repeater::make('files')
                            ->label('Link File Hasil Pekerjaan')
                            ->schema([
                                Forms\Components\TextInput::make('url')
                                    ->label('Link File')
                                    ->url()
                                    ->required()
                                    ->placeholder('https://drive.google.com/... atau link lainnya')
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama File')
                                    ->placeholder('Contoh: Video Final.mp4')
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->visible(fn (Forms\Get $get) => $get('new_status') === 'completed')
                            ->addActionLabel('Tambah Link File')
                            ->helperText('Masukkan link file hasil pekerjaan dari Google Drive, Dropbox, atau cloud storage lainnya')
                            ->collapsible(),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $order = $livewire->getOwnerRecord();
                        $newStatus = $data['new_status'];
                        
                        $statusLabels = [
                            'draft' => 'Draft',
                            'in_progress' => 'Dikerjakan',
                            'revision_1' => 'Revisi 1',
                            'revision_2' => 'Revisi 2',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                        
                        // Update order status
                        $order->update(['status' => $newStatus]);
                        
                        // Filter and prepare files data - ensure proper JSON encoding
                        $filesData = null;
                        if (!empty($data['files']) && is_array($data['files'])) {
                            // Filter out empty entries
                            $validFiles = array_filter($data['files'], function($file) {
                                return is_array($file) && !empty($file['url']);
                            });
                            
                            // Re-index array and ensure it's not empty
                            if (!empty($validFiles)) {
                                $filesData = json_encode(array_values($validFiles));
                            }
                        }
                        
                        // Create progress entry with files
                        $order->progress()->create([
                            'title' => 'Status Diubah: ' . $statusLabels[$newStatus],
                            'description' => $data['description'],
                            'status' => 'info',
                            'files' => $filesData,
                            'created_by' => auth()->id(),
                        ]);
                        
                        Notification::make()
                            ->title('Progress ditambahkan & status diupdate!')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        // Decode JSON files untuk form edit
                        if (!empty($data['files']) && is_string($data['files'])) {
                            $data['files'] = json_decode($data['files'], true);
                        }
                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        // Filter and prepare files data - ensure proper JSON encoding
                        if (!empty($data['files']) && is_array($data['files'])) {
                            // Filter out empty entries
                            $validFiles = array_filter($data['files'], function($file) {
                                return is_array($file) && !empty($file['url']);
                            });
                            
                            // Re-index array and manually JSON encode
                            $data['files'] = !empty($validFiles) ? json_encode(array_values($validFiles)) : null;
                        } else {
                            $data['files'] = null;
                        }
                        
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Progress')
                    ->modalDescription('Apakah Anda yakin ingin menghapus progress ini? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
