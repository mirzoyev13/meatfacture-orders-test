<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BirthdayController;
use App\Services\NotificationPushService;
use App\Jobs\CardJob;
use App\Jobs\RefundMoneyJob;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Задача 1
Route::post('/birthdays', [BirthdayController::class, 'update']);

// Задача 2
Route::get('/push', function () {
    NotificationPushService::send(1, 'Заказ в процессе обработки');
    return response()->json(['status' => 'OK']);
});


// Задача 3
Route::post('/credit-card', function (Request $request) {
    CardJob::dispatch(
        $request->user_id,
        $request->only(['card_number', 'cvv', 'expiry'])
    );

    return response()->json(['status' => 'Job is done']);
});
Route::post('/test-card', function () {
    return response()->json(['status' => 'test ok']);
});


// Задача 4
Route::post('/orders/{order}/cancel', function (Order $order) {
    $order->status = 'cancelled';
    $order->save();
    RefundMoneyJob::dispatch($order);

    return response()->json([
        'message' => "Заказ #{$order->id} отменён. Возврат выполнен.",
    ]);
});

