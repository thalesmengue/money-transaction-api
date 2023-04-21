<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class NotificationClient
{
    public function notify(): array
    {
        $response = Http::get(config('notification.url'));
        return $response->json();
    }
}
