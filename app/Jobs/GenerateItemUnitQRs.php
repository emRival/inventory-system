<?php

namespace App\Jobs;

use App\Models\Distribution;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateItemUnitQRs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $itemId;

    public function __construct($itemId)
    {
        $this->itemId = $itemId;
    }

    public function handle(): void
    {
        $item = Distribution::findOrFail($this->itemId);
        $folder = storage_path('app/public/qrs');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $toGenerate = $item->quantity - $item->itemUnits()->count();

        for ($i = 0; $i < $toGenerate; $i++) {
            $uuid = Str::uuid()->toString();
            $filename = "$uuid.png";
            $path = "$folder/$filename";

            if (!file_exists($path)) {
                $qrImage = file_get_contents("https://quickchart.io/qr?text=" . urlencode($uuid));
                file_put_contents($path, $qrImage);
            }

            $item->itemUnits()->create([
                'qr_code' => $uuid,
                'status' => 'aktif',
            ]);
        }

        

     
    }
}