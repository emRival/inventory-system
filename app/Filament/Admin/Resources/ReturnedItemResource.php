<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ItemUnitResource\Pages\EditItemUnit;
use App\Filament\Admin\Resources\ReturnedItemResource\Pages;
use App\Filament\Admin\Resources\ReturnedItemResource\RelationManagers;
use App\Models\ItemUnit;
use App\Models\ReturnedItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReturnedItemResource extends Resource
{
    protected static ?string $model = ItemUnit::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'returned');
    }

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Tracking Barang';
    protected static ?string $label = 'Item Returned';
    protected static ?string $slug = 'returned-items';
    protected static ?string $navigationLabel = 'Item Returned';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'returned')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReturnedItems::route('/'),
            'create' => Pages\CreateReturnedItem::route('/create'),
            'edit' => EditItemUnit::route('/{record}/edit'),
        ];
    }
}