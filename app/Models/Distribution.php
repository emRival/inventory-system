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

    protected static function booted(): void
{
    static::creating(function ($record) {
        $quantity = $record->quantity;
        $productId = $record->product_id;
        $condition = $record->condition;

        $stocks = Stock::where('product_id', $productId)
            ->where('condition', $condition)
            ->where('quantity', '>', 0)
            ->orderBy('created_at')
            ->get();

        foreach ($stocks as $stock) {
            if ($quantity <= 0) break;

            $deduct = min($stock->quantity, $quantity);
            $stock->decrement('quantity', $deduct);
            $quantity -= $deduct;
        }

        if ($quantity > 0) {
            throw new \Exception("Stok tidak mencukupi.");
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
