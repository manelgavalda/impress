<?php

namespace App\Http\Controllers;

use App\Subscription;
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

    public function index()
    {
        if(Subscription::whereState('PURCHASED')->exists()) {
            $response = $this->client->get($this->apiUrl);

            Subscription::updateStates($response);
        }

        return view('welcome', [
            'subscriptions' => Subscription::all()
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
        ])->json();

        if(optional($response)['statusCode'] == 202) {
            $data = $response['data'];

            Subscription::create([
                'id' => $data['id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'domain_name' => $data['domains'][0]['domain_name'],
                'state' => $data['provision_state']
            ]);

            $message = "{$data['first_name']} {$data['last_name']} created successfully.";
        } else {
            $message = 'Error when creating the new subscription.';
        }

        return redirect('/subscription')->with('message', $message);
    }

    public function destroy($id)
    {
        $response = $this->client->delete("{$this->apiUrl}/{$id}");

        $response = optional($response->json());

        if($response['statusCode'] == 202) {
            Subscription::find($id)->delete();

            $message = $response->message;
        } else {
            $message = 'Error when deleting the subscription';
        }

        return redirect('/subscription')->with('message', $message);
    }
}
