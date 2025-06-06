<?php

namespace App\Filament\Admin\Resources\ItemUnitRelationManagerResource\RelationManagers;

use App\Jobs\DispatchItemUnitQRsInBatch;
use App\Jobs\GenerateItemUnitQRsBatch;
use App\Models\ItemUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\BulkAction;

class ItemUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'itemUnits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Item QR Units')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        $record = $this->ownerRecord;

        $generated = $record->itemUnits()->count();
        $total = $record->quantity;

        return $table
            ->poll('5s')
            ->heading(new HtmlString("<div class='text-sm text-gray-600'>QR dibuat: <strong>$generated dari $total</strong></div>"))
            ->columns([
                Tables\Columns\ViewColumn::make('qr_code')
                    ->label('QR Preview')
                    ->view('components.qr-modal-preview'),

                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan')
                    ->wrap(), // Ensures text wraps to the next line if it doesn't fit,

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => match ($state) {
                        'aktif' => 'success',
                        'rusak' => 'danger',
                        'hilang' => 'warning',
                        'returned' => 'primary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('condition')
                    ->label('Condition')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => match ($state) {

                        'baru' => 'primary',
                        'bekas' => 'secondary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('return_note')
                    ->label('Return Note')
                    ->wrap()
                    ->toggleable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),

                Tables\Columns\TextColumn::make('created_at')->since(),
                Tables\Columns\TextColumn::make('return_date')->date()->toggleable(),
            ])->defaultSort('updated_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('Export')
                        ->icon('heroicon-m-arrow-down-tray')
                        // buang openUrlInNewTab() kalau mau streaming download
                        ->deselectRecordsAfterCompletion()
                        ->action(function (BulkAction $action, Collection $records) {
                            // sekarang $records adalah koleksi Model yang kamu pilih
                            // dd di sini pun akan menampilkan semua record, bukan cuma pertama
                            // dd($records->toArray());

                            $pdf = Pdf::loadView('pdf', [
                                'records' => $records,
                            ]);

                            return response()->streamDownload(
                                fn() => print($pdf->output()),
                                'Distribution-'.now()->format('YmdHis').'.pdf'
                            );
                        }),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('qr_code')
                            ->label('QR Code')
                            ->disabled(),
                        Forms\Components\TextInput::make('note')
                            ->label('Catatan')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'rusak' => 'Rusak',
                                'hilang' => 'Hilang',
                                'returned' => 'Returned',
                            ])
                            ->required()
                            ->reactive(),



                        Forms\Components\DatePicker::make('return_date')
                            ->label('Return Date')
                            ->visible(fn($get) => $get('status') === 'returned')
                            ->required(fn($get) => $get('status') === 'returned'),

                        Forms\Components\Textarea::make('return_note')
                            ->label('Return Note')
                            ->rows(3)
                            ->maxLength(255)
                            ->visible(fn($get) => $get('status') === 'returned')
                    ])
                    ->action(function ($record, array $data) {
                        // Jika status 'aktif' atau 'hilang', hapus return-related fields
                        if (in_array($data['status'], ['aktif', 'hilang'])) {
                            $data['return_note'] = null;
                            $data['return_date'] = null;
                        }

                        // Jika status 'returned', set tanggal jika belum ada
                        if ($data['status'] === 'returned') {
                            $data['return_date'] = $data['return_date'] ?? now();
                        }

                        $record->update($data);

                        Notification::make()
                            ->title('Data berhasil diperbarui')
                            ->success()
                            ->send();
                    })

            ])

            ->headerActions([
                // ✅ Bulk generate QR
                Action::make('Generate All QR Codes')
                    ->visible(fn() => $this->ownerRecord->itemUnits()->count() < $this->ownerRecord->quantity)
                    ->label('Generate All')
                    ->requiresConfirmation()
                    ->action(function () {
                        $userId = Auth::id(); // ambil ID user yang login
                        DispatchItemUnitQRsInBatch::dispatch($this->ownerRecord->id, $userId);

                        Notification::make()
                            ->title("Proses generate QR sedang dijalankan bertahap")
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-plus'),


                // ✅ Single QR Generator with Modal Note
                Action::make('AddOneWithNote')
                    ->label('Tambah 1 QR')
                    ->visible(fn() => $this->ownerRecord->itemUnits()->count() < $this->ownerRecord->quantity)
                    ->icon('heroicon-o-plus-small')
                    ->form([
                        Forms\Components\TextInput::make('product')
                            ->label('Produk')
                            ->default($this->ownerRecord->product->name)
                            ->disabled(),

                        Forms\Components\TextInput::make('condition')
                            ->label('Kondisi')
                            ->default($this->ownerRecord->condition)
                            ->disabled(),

                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->rows(3)
                            ->maxLength(255),
                    ])
                    ->action(function (array $data) {
                        $itemId = $this->ownerRecord->id;
                        $note   = $data['note'] ?? null;
                        $userId = Auth::id();

                        // Dispatch job untuk 1 QR di background
                        GenerateItemUnitQRsBatch::dispatch($itemId, 1, $userId, $note);


                        // Tampilkan toast bahwa job sudah dijalankan
                        Notification::make()
                            ->title('1 QR code sedang dibuat di background')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}