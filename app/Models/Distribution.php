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
        static::created(function (self $dist) {
            Stock::where('product_id', $dist->product_id)
                ->where('condition', $dist->condition)
                ->decrement('quantity', $dist->quantity);
        });

        static::updating(function (self $dist) {
            // Hindari double eksekusi jika ini ternyata creation disguised as update
            if (!$dist->exists || !$dist->isDirty('quantity')) {
                return;
            }

            $originalQuantity = $dist->getOriginal('quantity');
            $originalProductId = $dist->getOriginal('product_id');
            $originalCondition = $dist->getOriginal('condition');

            $newQuantity = $dist->quantity;
            $newProductId = $dist->product_id;
            $newCondition = $dist->condition;

            if (
                $originalProductId !== $newProductId ||
                $originalCondition !== $newCondition
            ) {
                Stock::where('product_id', $originalProductId)
                    ->where('condition', $originalCondition)
                    ->increment('quantity', $originalQuantity);

                Stock::where('product_id', $newProductId)
                    ->where('condition', $newCondition)
                    ->decrement('quantity', $newQuantity);
            } else {
                $delta = $newQuantity - $originalQuantity;

                if ($delta > 0) {
                    Stock::where('product_id', $newProductId)
                        ->where('condition', $newCondition)
                        ->decrement('quantity', $delta);
                } elseif ($delta < 0) {
                    Stock::where('product_id', $newProductId)
                        ->where('condition', $newCondition)
                        ->increment('quantity', abs($delta));
                }
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