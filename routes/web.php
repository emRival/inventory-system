<?php

use App\Http\Controllers\ItemUnitPublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // redirect to admin/login
    return redirect('/admin/login');
});

Route::get('/item-units/{qr_code}', [ItemUnitPublicController::class, 'show'])->name('item-units.show');
