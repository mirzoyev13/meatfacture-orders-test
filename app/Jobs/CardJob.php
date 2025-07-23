<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class CardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $userId;
    protected array $cardData;
    public $tries = 3; // для повтора
    public $backoff = [1, 2, 4]; // для задержки между повторами

    public function __construct(string $userId, array $cardData)
    {
        $this->userId = $userId;
        $this->cardData = $cardData;
    }

    public function handle(): void
    {
        try {
            $response = Http::post('http://127.0.0.1:8000/api/test-card', $this->cardData);
            if ($response->successful()) {
                Log::info("Карта привязана к юзеру -{$this->userId}");
            } elseif (in_array($response->status(), [502, 504])) {
                Log::warning("Ошибка: {$response->status()}");
                throw new \Exception("Temporary error");
            } else {
                Log::error("Ошибка связки: {$response->status()} — {$response->body()}");
            }
        } catch (\Throwable $e) {
            Log::error("Ошибка связки2: {$e->getMessage()}");
            throw $e;
        }
    }
}

