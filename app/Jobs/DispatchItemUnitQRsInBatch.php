<?php

namespace App\Jobs;

use App\Models\Distribution;
use App\Models\Item;
use App\Jobs\GenerateItemUnitQRsBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\ItemUnitQRsGeneratedNotification;

class DispatchItemUnitQRsInBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $itemId, public int $userId) {}

    public function handle(): void
    {
        $item = Distribution::findOrFail($this->itemId);
        $remaining = $item->quantity - $item->itemUnits()->count();

        $batchSize = 10;
        $batches = ceil($remaining / $batchSize);

        for ($i = 0; $i < $batches; $i++) {
            $amount = min($batchSize, $remaining - $i * $batchSize);

            GenerateItemUnitQRsBatch::dispatch(
                $this->itemId,
                $amount,
                $this->userId         // <-- tambahkan ini
            );
        }
    }
}