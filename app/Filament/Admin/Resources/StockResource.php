<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StockResource\Pages;
use App\Filament\Admin\Resources\StockResource\RelationManagers;
use App\Models\Product;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box'; // Stok gudang
    protected static ?string $navigationGroup = 'Gudang Pusat';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix(fn(callable $get) => $get('unit')),

                Forms\Components\Hidden::make('unit'),
                Forms\Components\Select::make('condition')
                    ->required()
                    ->options([
                        'baru' => 'Baru',
                        'bekas' => 'Bekas',
                    ]),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('condition')
                    ->label('Condition')
                    ->options([
                        'baru' => 'Baru',
                        'bekas' => 'Bekas',
                    ]),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock < 10')
                    ->query(fn(Builder $query) => $query->where('quantity', '<', 10)),

                Tables\Filters\Filter::make('high_stock')
                    ->label('Stock > 10')
                    ->query(fn(Builder $query) => $query->where('quantity', '>=', 10)),
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
