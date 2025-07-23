<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundMoney extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'status',
        'message',
        'refunded_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
