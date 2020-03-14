<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::redirect('/', 'subscription');

Route::resource('subscription', 'SubscriptionController')->only([
    'index', 'store', 'update', 'destroy'
]);

Route::get('/create-token', function () {
    $url = 'https://app.deva.common.impress.website/oauth/token';
    $clientId = 24;
    $clientSecret = 'ojQj6l/dO4u1jeywH0nQs9WaFx5ZAR5VS+IwArexvTw=';
    $grantType = 'client_credentials';

    $response = Http::post($url, [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => $grantType
    ]);

    $response = json_decode($response->body());

    return $response->access_token;
});
