<?php

use App\Http\Controllers\StripePaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::name('stripe.')
//     ->controller(StripePaymentController::class)
//     ->prefix('stripe')
//     ->group(function () {
//         Route::get('payment', 'index')->name('index');
//         Route::post('payment', 'store')->name('store');
//     });

// Route::get('/stripe-index',[StripePaymentController::class,'index'])->name('stripe.index');
// Route::post('/stripe-store',[StripePaymentController::class,'store'])->name('stripe.store');

// Route::get('/payment-status',[StripePaymentController::class,'paymentStatus'])->name('payment.status');

// Route::get('/payment-status', function () {
//     return view('stripe.payment-success');
// })->name('payment.status');


Route::post('/create-payment-intent', [StripePaymentController::class, 'createPaymentIntent']);
Route::post('/store-payment', [StripePaymentController::class, 'storeNewPayment'])->name('stripe.storenew');
