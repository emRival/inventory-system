<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'latitude',
        'longitude',
    ];

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}
