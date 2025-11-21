<?php

namespace App\Modules\Notifications\Services;

use App\Modules\Notifications\Models\CoreNotification;

class NotificationService
{
    public static function notify(array $data): CoreNotification
    {
        return CoreNotification::create([
            'module'       => $data['module'],
            'title'        => $data['title'],
            'message'      => $data['message'],
            'type'         => $data['type'] ?? 'info',
            'context'      => $data['context'] ?? null,
            'status'       => $data['status'] ?? 'active',
            'meta'         => $data['meta'] ?? null,
            'url'          => $data['url'] ?? null,
            'triggered_at' => now(),
        ]);
    }

    public static function notifyOnce(array $data): ?CoreNotification
    {
        $exists = CoreNotification::where('module', $data['module'])
            ->where('context', $data['context'] ?? null)
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            return null;
        }

        return self::notify($data);
    }
}
