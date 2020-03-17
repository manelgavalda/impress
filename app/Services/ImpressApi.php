<?php

namespace App\Services;

use DB;
use Http;

class ImpressApi
{
    public function client()
    {
        return Http::withToken($this->token());
    }

    protected function token() {
        if(! DB::table('tokens')->latest()->first()) {
            $this->createToken();
        }

        return DB::table('tokens')->latest()->first()->api_token;
    }

    protected function createToken() {
        $response = Http::post(config('impress.url') . '/oauth/token', [
            'client_id' => config('impress.client_id'),
            'client_secret' => config('impress.client_secret'),
            'grant_type' => 'client_credentials'
        ]);

        DB::table('tokens')->insert([
            'api_token' => $response->json()['access_token']
        ]);
    }
}
