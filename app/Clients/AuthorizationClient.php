<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class AuthorizationClient
{
    public function authorize(): array
    {
        $response = Http::get(config('authorization.url'));
        return $response->json();
    }
}
