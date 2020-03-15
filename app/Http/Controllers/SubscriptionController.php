<?php

namespace App\Http\Controllers;

use DB;
use Http;

class SubscriptionController extends Controller
{
    protected $url = "https://app.deva.common.impress.website/api/subscription";

    /**
     * If you have one item, it just returns the item ?
     */
    public function index()
    {
        return view('welcome', [
            'subscriptions' => json_decode($this->client()->get($this->url))->data
        ]);
    }

    public function store()
    {
        $this->client()->post($this->url, [
            "first_name" => request('first_name'),
            "last_name" => request('last_name'),
            "email" => request('email'),
            "domain_name" => request('domain_name') . '.deva.impress.website',
            "external_user_id" => request('external_user_id'),
            "external_subscription_id" => request('external_subscription_id'),
            "product_id" => request('product_id'),
            "state" => request('state'),
            "provision_state" => request('provision_state')
        ]);

        return redirect('/subscription');
    }

    public function update($id)
    {
        $this->client()->put($this->url, [
            'id' => $id,
            'state' => request('state')
        ]);

        redirect('/subscription');
    }

    public function destroy($id)
    {
        $this->client()->delete("{$this->url}/{$id}");

        redirect('/subscription');
    }

    protected function client()
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
        $response = Http::post('https://app.deva.common.impress.website/oauth/token', [
            'client_id' => 24,
            'client_secret' => 'ojQj6l/dO4u1jeywH0nQs9WaFx5ZAR5VS+IwArexvTw=',
            'grant_type' => 'client_credentials'
        ]);

        DB::table('tokens')->insert([
            'api_token' => json_decode($response->body())->access_token
        ]);
    }
}
