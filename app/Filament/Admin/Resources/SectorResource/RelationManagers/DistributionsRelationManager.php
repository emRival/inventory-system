<?php

namespace App\Filament\Admin\Resources\SectorResource\RelationManagers;

use App\Models\Product;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributionsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributions';

    public function form(Form $form): Form
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
                        $unit = Product
                            ::find($state)?->unit ?? '';
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


                Forms\Components\DateTimePicker::make('date')
                    ->maxDate(now()->addHour(23)->addMinute(59))
                    ->timezone('Asia/Jakarta')

                    ->required(),

                Forms\Components\Hidden::make('unit'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    //go to distribution item units
                    ->url(fn($record) => route('filament.admin.resources.distributions.edit', [
                        'record' => $record->id,
                    ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}