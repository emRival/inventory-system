<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    use HasFactory;

    protected $primaryKey = 'qr_code';

    protected $casts = ['qr_code' => 'string'];
    // protected $keyType = 'string';

    protected $fillable = [
        'distribution_id',
        'qr_code',
        'note',
        'status',
        'return_date',
        'return_note',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class);
    }
}
