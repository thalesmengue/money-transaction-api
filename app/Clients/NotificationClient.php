<?php

namespace App\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class NotificationClient
{
    public function notify(): Response
    {
        return Http::get(config('notification.url'));
    }
}
