<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ItemUnit extends Model
{
    use HasFactory;

    protected $primaryKey = 'qr_code'; // Correct
    public $incrementing = false;    // Crucial: Tell Eloquent 'qr_code' is not auto-incrementing
    protected $keyType = 'string';


    protected $casts = [
        'qr_code' => 'string',     // Good, reinforces it's a string
        'return_date' => 'date',   // Example: Good practice to cast dates
    ];

    protected $fillable = [
        'distribution_id',
        'qr_code',
        'note',
        'status',
        'condition',
        'return_date',
        'return_note',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class);
    }

    protected static function booted()
    {
        // Untuk hard delete (atau jika tidak pakai soft deletes)
        static::deleting(function (ItemUnit $unit) {
            // Jika pakai soft deletes, hanya hapus file saat benar2 forceDelete
            if (method_exists($unit, 'isForceDeleting') && ! $unit->isForceDeleting()) {
                return;
            }

            // Hapus file PNG berdasarkan qr_code
            Storage::disk('public')->delete("qrs/{$unit->qr_code}.png");
        });
    }
}