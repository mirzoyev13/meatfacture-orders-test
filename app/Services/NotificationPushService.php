<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NotificationPushService
{
    /**
     * @param int $userId // есть фактори
     * @param string $message
     */
    public static function send(int $userId, string $message): void
    {
        $key = self::generateKey($userId, $message);
        $isNew = Cache::add($key, true, now()->addDay());

        if (!$isNew) {
            Log::info("дубликат для юзера - {$userId}");
            return;
        }

        Log::info("Заглушка что отправился пуш {$userId}: {$message}");
    }
    protected static function generateKey(int $userId, string $message): string
    {
        return 'push_lock:' . hash('sha256', "{$userId}:{$message}");
    }
}
