<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SectorResource\Pages;
use App\Filament\Admin\Resources\SectorResource\RelationManagers;
use App\Filament\Admin\Resources\SectorResource\RelationManagers\DistributionsRelationManager;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
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

    protected static ?int $navigationSort = 3;

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
        return $form
            ->schema([
                Section::make('Sector Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Sector Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->label('Location Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Map Preview')
                    ->schema([
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

                    ]),
                //section view
                Section::make('Map Initialization')
                    ->visibleOn(['create','edit'])
                    ->schema([
                        View::make('filament.geo-init')
                    ]),

                Section::make('Coordinates')
                    // deskripsi bahwa cordinate ini hanya read tidak perlu di ubah dan akan otomatis terubah jika create button di tekan
                    ->description('Koordinat ini akan terisi otomatis berdasarkan lokasi Anda saat ini. Jika ingin mengubahnya, silahkan update lokasi pada menu edit.')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->required()
                            ->readOnly()
                            ->default(0)
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->required()
                            ->default(0)
                            ->readOnly()

                            ->numeric(),
                    ])
                    ->columns(2),
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
                Tables\Actions\ViewAction::make(),
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
            DistributionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSectors::route('/'),
            'create' => Pages\CreateSector::route('/create'),
            'edit' => Pages\EditSector::route('/{record}/edit'),
            'view' => Pages\ViewSector::route('/{record}'), // Removed due to undefined class

        ];
    }
}
