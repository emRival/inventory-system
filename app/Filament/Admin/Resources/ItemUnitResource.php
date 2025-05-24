<?php

namespace App\Filament\Admin\Resources;

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
            ->columns([
                Tables\Columns\TextColumn::make('qr_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('distribution.sector.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('distribution.product.category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('distribution.product.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->description(function ($state, $record) {
                        if ($state === 'returned' && !empty($record->return_note)) {
                            return $record->return_note;
                        }
                        return null;
                    })->wrap()
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions(
                [
                    ExportAction::make()
                        ->exporter(ItemUnitExporter::class)
                        ->label('Ekspor')
                   
                ]
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListItemUnits::route('/'),
            // 'create' => Pages\CreateItemUnit::route('/create'),
            'edit' => Pages\EditItemUnit::route('/{record}/edit'),
        ];
    }
}