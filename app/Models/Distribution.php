<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sector_id',
        'quantity',
        'condition',
        'date',
    ];

    protected static function booted()
    {
        // on create: subtract the full quantity
        static::created(function (self $dist) {
            Stock::where('product_id', $dist->product_id)
                ->where('condition', $dist->condition)
                ->decrement('quantity', $dist->quantity);
        });

        // on update: subtract or add _only_ the delta
        static::updating(function (self $dist) {
            $original = $dist->getOriginal('quantity');
            $new      = $dist->quantity;
            $delta    = $new - $original;

            // dd($delta);

            if ($delta > 0) {
                // user increased from 10 → 11, so take 1 more
                Stock::where('product_id', $dist->product_id)
                    ->where('condition', $dist->condition)
                    ->decrement('quantity', $delta);
            } elseif ($delta < 0) {
                // user reduced from 10 → 8, so give back 2
                Stock::where('product_id', $dist->product_id)
                    ->where('condition', $dist->condition)
                    ->increment('quantity', abs($delta));
            }
        });
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function itemUnits()
    {
        return $this->hasMany(ItemUnit::class);
    }
}
