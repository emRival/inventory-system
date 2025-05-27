<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DistributionResource\Pages;
use App\Filament\Admin\Resources\DistributionResource\RelationManagers\ItemUnitRelationManager;
use App\Filament\Admin\Resources\ItemUnitRelationManagerResource\RelationManagers\ItemUnitsRelationManager;
use App\Models\Distribution;
use App\Models\Product;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributionResource extends Resource
{
    protected static ?string $model = Distribution::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck'; // Distribusi barang
    protected static ?string $navigationGroup = 'Distribusi';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }



    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->preload()
                ->searchable()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    $unit = Product::find($state)?->unit ?? '';
                    $set('unit', $unit);
                }),

            Forms\Components\Select::make('condition')
                ->required()
                ->options([
                    'baru' => 'Baru',
                    'bekas' => 'Bekas',
                ])
                ->live(),

            Forms\Components\TextInput::make('quantity')
                ->required()
                ->numeric()
                ->default(0)
                ->minValue(1)
                ->reactive()
                ->suffix(fn(callable $get) => $get('unit'))
                ->helperText(function (callable $get) {
                    $productId = $get('product_id');
                    $condition = $get('condition');
                    if (!$productId || !$condition) return 'Pilih produk dan kondisi';

                    $totalStock = Stock::where('product_id', $productId)
                        ->where('condition', $condition)
                        ->sum('quantity');

                    return "Stok tersedia: $totalStock";
                })
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $productId = $get('product_id');
                    $condition = $get('condition');

                    if (!$productId || !$condition) return;

                    $totalStock = Stock::where('product_id', $productId)
                        ->where('condition', $condition)
                        ->sum('quantity');

                    if ($state > $totalStock) {
                        $set('quantity', null);

                        Notification::make()
                            ->title('Stok Tidak Cukup')
                            ->body("Stok tersedia hanya $totalStock unit.")
                            ->danger()
                            ->send();
                    }
                }),

            Forms\Components\Select::make('sector_id')
                ->relationship('sector', 'name')
                ->required()
                ->label('Sector')
                ->preload()
                ->searchable(),

            Forms\Components\DateTimePicker::make('date')
                ->maxDate(now()->addHour(23)->addMinute(59))
                ->timezone('Asia/Jakarta')

                ->required(),

            Forms\Components\Hidden::make('unit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sector.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->suffix(fn($record) => ' ' . $record->product?->unit),

                Tables\Columns\TextColumn::make('returned_quantity')
                    ->label('Returned Quantity')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->itemUnits()->where('status', 'returned')->count())
                    ->suffix(fn($record) => ' ' . $record->product?->unit),
                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => match ($state) {
                        'baru' => 'success',
                        'bekas' => 'warning',
                    })
                    ->icon(fn($state) => match ($state) {
                        'baru' => 'heroicon-o-check-circle',
                        'bekas' => 'heroicon-o-clock',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('returned_items')
                    ->label('Returned Items')
                    ->query(function (Builder $query) {
                        $query->whereHas('itemUnits', function (Builder $subQuery) {
                            $subQuery->where('status', 'returned');
                        });
                    }),

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
                            $query->whereDate('date', '>=', $data['start_date']);
                        }
                        if ($data['end_date'] ?? null) {
                            $query->whereDate('date', '<=', $data['end_date']);
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDistributions::route('/'),
            'create' => Pages\CreateDistribution::route('/create'),
            'edit' => Pages\EditDistribution::route('/{record}/edit'),
        ];
    }
}