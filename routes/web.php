<?php

use App\Http\Controllers\ItemUnitPublicController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // redirect to admin/login
    return redirect('/admin/login');
});

// Route::get('/item-units/{qr_code}', [ItemUnitPublicController::class, 'show'])->name('item-units.show');

// Route::get('/test-email', function () {
//     Mail::raw('Test email from Laravel', function ($message) {
//         $message->to('em.rival@idn.sch.id')
//                 ->subject('Tes Email Laravel');
//     });

//     return 'Email sent!';
// });
