<?php

namespace App\Jobs;

use App\Models\Distribution;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Filament\Notifications\Notification;
use App\Notifications\QRGenerationCompleted;

class GenerateItemUnitQRsBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Retry hingga 3 kali jika job timeout atau exception tak tertangani
    public int $tries   = 3;
    // Timeout per job 5 menit
    public int $timeout = 300;

    public function __construct(
        public int    $itemId,
        public int    $amount,
        public int    $userId,
        public string $note = '',
    ) {}

    public function handle(): void
    {
        // 1) Load item dan siapkan folder
        $item   = Distribution::findOrFail($this->itemId);
        $folder = storage_path('app/public/qrs');

        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        // 2) Buat QR sebanyak $this->amount; kalau ada error sekali saja langsung notify & stop
        $created = 0;
        while ($created < $this->amount) {
            try {
                // generate UUID dan path
                $uuid     = Str::uuid()->toString();
                $filename = "$uuid.png";
                $path     = "$folder/$filename";

                // fetch & simpan image
                if (! file_exists($path)) {
                    $qrImage = @file_get_contents("https://quickchart.io/qr?text=" . urlencode($uuid));
                    if (! $qrImage) {
                        throw new \Exception("Gagal mengambil QR untuk $uuid");
                    }
                    file_put_contents($path, $qrImage);
                }

                // simpan record ke DB
                $item->itemUnits()->create([
                    'qr_code' => $uuid,
                    'status'  => 'aktif',
                    'condition' => $item->condition,
                    'note'    => $this->note,
                ]);

                $created++;
            } catch (\Throwable $e) {
                // 3) Notifikasi error ke user, langsung berhenti
                if ($user = User::find($this->userId)) {
                    Notification::make()
                        ->title('⚠️ Gagal Generate QR')
                        ->body($e->getMessage())
                        ->danger()
                        ->sendToDatabase($user);
                }
                return;
            }
        }

        // 4) Jika semua batch selesai, kirim notifikasi sukses
        if ($user = User::find($this->userId)) {
            $message = $this->amount === 1
                ? "1 QR untuk “{$item->product->name}” berhasil dibuat."
                : "{$this->amount} QR untuk “{$item->product->name}” berhasil dibuat.";

            Notification::make()
                ->title('✅ QR Generation Completed')
                ->body($message)
                ->success()
                ->sendToDatabase($user);
        }
    }
}