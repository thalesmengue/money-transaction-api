<?php

namespace App\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AuthorizationClient
{
    public function authorize(): string
    {
        return Http::get(config('authorization.url'))
            ->json('message');
    }
}
