<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SectorResource\Pages;
use App\Filament\Admin\Resources\SectorResource\RelationManagers;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectorResource extends Resource
{
    protected static ?string $model = Sector::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront'; // Sektor tujuan
    protected static ?string $navigationGroup = 'Distribusi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                        OSMMap::make('locations')
                            ->label('Locations')
                            ->showMarker()
                            ->draggable()
                            ->zoom(18)

                            ->extraControl([
                                'zoomDelta'           => 1,
                                'zoomSnap'            => 0.25,
                                'wheelPxPerZoomLevel' => 60
                            ])
                            ->afterStateHydrated(function (Set $set, $record) {

                                $latitude = $record->latitude ?? 0;
                                $longitude = $record->longitude ?? 0;


                                $set('locations', [
                                    'lat' => $latitude,
                                    'lng' => $longitude,
                                    'minZoom' => 1,
                                    'maxZoom' => 23,
                                    'zoom' => 18,
                                    'ext' => 'png'
                                ]);
                            })
                            ->afterStateUpdated(function ($state, Set $set) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            ->tilesUrl('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),

                        Group::make([
                            Forms\Components\TextInput::make('latitude')
                                ->required()
                                ->default(0)
                                ->numeric(),
                            Forms\Components\TextInput::make('longitude')
                                ->required()
                                ->default(0)
                                ->numeric(),
                        ])->columns(2),

                    ])
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
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
            'index' => Pages\ListSectors::route('/'),
            'create' => Pages\CreateSector::route('/create'),
            'edit' => Pages\EditSector::route('/{record}/edit'),
        ];
    }
}