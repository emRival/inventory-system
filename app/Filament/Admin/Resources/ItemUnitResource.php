<?php

namespace App\Filament\Admin\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Admin\Resources\ItemUnitResource\Pages;
use App\Filament\Admin\Resources\ItemUnitResource\RelationManagers;
use App\Filament\Exports\ItemUnitExporter;
use App\Models\ItemUnit;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf; // Import the Pdf facade from barryvdh/laravel-dompdf

class ItemUnitResource extends Resource
{
    protected static ?string $model = ItemUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code'; // Unit item QR
    protected static ?string $navigationGroup = 'Tracking Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make([

                        Forms\Components\TextInput::make('qr_code')
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


                    ])
                ]),

                Group::make([
                    Section::make([
                        Forms\Components\DatePicker::make('return_date')
                            ->label('Return Date')
                            ->required(fn($get) => $get('status') === 'returned'),
                        Forms\Components\Textarea::make('return_note')
                            ->label('Return Note')
                            ->rows(3)
                            ->maxLength(255)
                    ])
                ])->visible(fn($get) => $get('status') === 'returned'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordKey('qr_code')
            ->columns([
                Tables\Columns\TextColumn::make('qr_code') // This is your primary key
                    ->label('QR Code (Primary Key)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qr_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('distribution.sector.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('distribution.product.category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('distribution.product.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->label('Condition')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => match ($state) {
                        'baru' => 'primary',
                        'bekas' => 'secondary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()

                    ->color(fn($state) => match ($state) {
                        'aktif' => 'success',
                        'rusak' => 'danger',
                        'hilang' => 'warning',
                        'returned' => 'primary',
                        default => 'secondary',
                    }),


                Tables\Columns\TextColumn::make('note')
                    ->label('Description')
                    ->wrap(),


                Tables\Columns\TextColumn::make('return_note')
                    ->label('Return Note')
                    ->wrap(),

                Tables\Columns\TextColumn::make('return_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('distribution.created_at')
                    ->label('Distribution Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('distribution_sector')
                    ->label('Sector')
                    ->relationship('distribution.sector', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'rusak' => 'Rusak',
                        'hilang' => 'Hilang',
                        'returned' => 'Dikembalikan',
                    ]),
                Tables\Filters\Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['start_date'] ?? null) {
                            $query->whereDate('created_at', '>=', $data['start_date']);
                        }
                        if ($data['end_date'] ?? null) {
                            $query->whereDate('created_at', '<=', $data['end_date']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions(
                [

                    FilamentExportHeaderAction::make('export')
                        ->disableAdditionalColumns() // Disable additional columns input
                ]
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // For debugging Delete
                            $keys = $records->modelKeys(); // Should be qr_codes
                            \Illuminate\Support\Facades\Log::info('DeleteBulkAction - Record Keys being processed:', $keys);
                            if (empty($keys)) {
                                \Illuminate\Support\Facades\Log::warning('DeleteBulkAction - No record keys found for deletion.');
                            }
                            // dd('DeleteBulkAction record keys:', $keys);
                        }),
                    Tables\Actions\BulkAction::make('Export')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (BulkAction $action, \Illuminate\Database\Eloquent\Collection $records) { // Typehint Collection



                            $pdf = Pdf::loadView('pdf', [
                                'records' => $records,
                            ]);

                            return response()->streamDownload(
                                fn() => print($pdf->output()),
                                'item-units.pdf'
                            );
                        }),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // protected function shouldSelectCurrentPageOnly(): bool
    // {
    //     return true;
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemUnits::route('/'),
            // 'create' => Pages\CreateItemUnit::route('/create'),
            'edit' => Pages\EditItemUnit::route('/{record}/edit'),
        ];
    }
}