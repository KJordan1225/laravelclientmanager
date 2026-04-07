<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Str;

class ClientCodeService
{
    public function generate(string $companyName): string
    {
        $prefix = Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $companyName), 0, 4));

        if (blank($prefix)) {
            $prefix = 'CLNT';
        }

        do {
            $code = $prefix . '-' . random_int(1000, 9999);
        } while (Client::where('client_code', $code)->exists());

        return $code;
    }
}