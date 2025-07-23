<?php

namespace App\Http\Controllers;

use App\Jobs\RefundMoneyJob;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function cancel(Order $order): JsonResponse
    {
        $order->update(['status' => 'cancelled']);

        RefundMoneyJob::dispatch($order);

        return response()->json(['message' => 'Заказ отменен и деньги возвращены']);
    }
}
