<?php

namespace App\Http\Controllers;

use App\Services\ImpressApi;

class SubscriptionController extends Controller
{
    protected $client;

    protected $apiUrl;

    function __construct()
    {
        $api = new ImpressApi;

        $this->client = $api->client();

        $this->apiUrl = config('impress.url') . '/api/subscription';
    }

    /**
     * If you have one item, it just returns one item ?
     */
    public function index()
    {
        $response = $this->client->get($this->apiUrl);

        return view('welcome', [
            'subscriptions' => json_decode($response)->data
        ]);
    }

    public function store()
    {
        $response = $this->client->post($this->apiUrl, [
            "first_name" => request('first_name'),
            "last_name" => request('last_name'),
            "email" => request('email'),
            "domain_name" => request('domain_name') . '-deva.demo.impress.website',
            "external_user_id" => request('external_user_id'),
            "external_subscription_id" => request('external_subscription_id'),
            "product_id" => "3",
            "state" => 'PURCHASED',
        ]);

        $response = optional(json_decode($response));

        if($response->statusCode == 202) {
            $message = "{$response->data->first_name} {$response->data->last_name} created successfully.";
        } else {
            $message = 'Error when creating the new subscription.';
        }

        return redirect('/subscription')->with('message', $message);
    }

    public function update($id)
    {
        $this->client->put($this->apiUrl, [
            'id' => $id,
            'state' => request('state')
        ]);

        return redirect('/subscription');
    }

    public function destroy($id)
    {
        $response = $this->client->delete("{$this->apiUrl}/{$id}");

        $response = optional(json_decode($response));

        if($response->statusCode == 202) {
            $message = $response->message;
        } else {
            $message = 'Error when deleting the subscription';
        }

        return redirect('/subscription')->with('message', $message);
    }
}
