<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Illuminate\Http\Request;

class ItemUnitPublicController extends Controller
{
    public function show($qr_code)
    {
        $unit = ItemUnit::where('qr_code', $qr_code)->firstOrFail();

        return view('public.item-unit-detail', [
            'unit' => $unit,
        ]);
    }
}
