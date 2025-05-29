<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Sector;
use Webbingbrasil\FilamentMaps\Actions;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

class Map extends MapWidget
{

    protected int | string | array $columnSpan = 2;

    protected bool $hasBorder = false;

    protected string | array  $tileLayerUrl = [
        'ArcGIS' => 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        'OpenStreetMap' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'OpenTopoMap' => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',

    ];

    protected array $tileLayerOptions = [
        'OpenStreetMap' => [
            'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        ],
        'OpenTopoMap' => [
            'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, SRTM | Map style © <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
        ],
        'ArcGIS' => [
            'attribution' => 'Tiles © Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
        ],
    ];





    public function getMarkers(): array
    {
        return Sector::with(['distributions.product'])->get()->map(function ($sector) {
            $popup = "<strong>{$sector->name}</strong><a href='https://www.google.com/maps?q={$sector->latitude},{$sector->longitude}' target='_blank'> (Google Maps)</a><br><br><ul>";
            foreach ($sector->distributions as $dist) {
                $popup .= "<li>{$dist->product?->name} ({$dist->condition}) - {$dist->quantity} {$dist->product?->unit}</li>";
            }
            $popup .= "</ul>";

            return Marker::make('sector-' . $sector->id)
                ->lat($sector->latitude)
                ->lng($sector->longitude)
                ->popup($popup);
        })->toArray();
    }

    public function getActions(): array
    {
        return [
            \Webbingbrasil\FilamentMaps\Actions\ZoomAction::make(),
            \Webbingbrasil\FilamentMaps\Actions\CenterMapAction::make()->zoom(2),
            Actions\Action::make('toggleTile')
                ->icon('heroicon-o-map')
                ->tooltip('Ganti Mode Peta')
                ->alpineClickHandler(<<<'JS'
        let layers = Object.keys(tileLayers);
        let currentLayerName = window.currentTileLayer || layers[0];

        let currentIndex = layers.indexOf(currentLayerName);
        let nextIndex = (currentIndex + 1) % layers.length;
        let nextLayer = layers[nextIndex];

        setTileLayer(nextLayer);
        window.currentTileLayer = nextLayer;
    JS)
        ];
    }
}
