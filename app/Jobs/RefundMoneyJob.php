<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\RefundMoneyNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Order;

class RefundMoneyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        try {
            $response = Http::post('http://127.0.0.1:8000/api/refund', [
                'order_id' => $this->order->id,
                'amount'   => $this->order->total,
                'user_id'  => $this->order->user_id,
            ]);

            if ($response->successful()) {
                $this->logRefund('success', $response->json());
                $this->notifyAdmin("Выполнен возврат средств по заказу #{$this->order->id}");
            } else {
                $this->logRefund('failed', $response->body());
                $this->notifyAdmin("Не удалось выполнить возврат средств по заказу #{$this->order->id}");
                throw new \Exception('Refund failed');
            }
        } catch (\Throwable $e) {
            $this->logRefund('error', $e->getMessage());
            $this->notifyAdmin("Ошибка возврата - #{$this->order->id}");
            throw $e;
        }
    }

    protected function logRefund(string $status, string|array $details): void
    {
        RefundLog::create([
            'order_id'  => $this->order->id,
            'user_id'   => $this->order->user_id,
            'amount'    => $this->order->total,
            'status'    => $status,
            'details'   => is_array($details) ? json_encode($details) : $details,
        ]);
    }

    protected function notifyAdmin(string $message): void
    {
        Notification::route('mail', 'admin@masofaktura.ru')
            ->notify(new RefundMoneyNotification($message));
    }

}
