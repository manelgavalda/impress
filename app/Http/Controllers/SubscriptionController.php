<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    protected $url = "https://app.deva.common.impress.website/api/subscription";

    /**
     * If you have one item, it just returns the item
     */
    public function index()
    {
        $response = $this->client()->get($this->url);

        return view('welcome', [
            'subscriptions' => json_decode($response)->data
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
            "state" => request('state')
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
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE0MDE4ZGZkOGUyYjQ1MGIwYTBlN2EyZGMxNDVhMTNlMDUxYzhhNmY5NDJlMmNmYWQ0NWFkMDM1Yjk4ZTkyNzRhZTg3ZjczZDNjMGI0ZjcxIn0.eyJhdWQiOiIyNCIsImp0aSI6ImE0MDE4ZGZkOGUyYjQ1MGIwYTBlN2EyZGMxNDVhMTNlMDUxYzhhNmY5NDJlMmNmYWQ0NWFkMDM1Yjk4ZTkyNzRhZTg3ZjczZDNjMGI0ZjcxIiwiaWF0IjoxNTg0MjEwNTQ0LCJuYmYiOjE1ODQyMTA1NDQsImV4cCI6MTYxNTc0NjU0NCwic3ViIjoiIiwic2NvcGVzIjpbXSwiaW50ZWdyYXRpb24iOiI0Y2Y0MTYzZi04NDhlLTQwNDctODQ2ZC1hYmYzMmQ2OGQ4OGEiLCJyZXNlbGxlciI6IjE3YTZlYjJkLTlhOWQtNDVkZi1iOTc2LWFhMjIyNTViNjg4NyJ9.Q1EgootCeBduZm9cS2fWpSzA40bWorVPun0vXyZpQ620YdUSDdm3U3P-DVzef2tuwiDOQpH11mVcKV_D5z2i20KiPP_UStg8aUMNuxl6ZZGfJJvS4nISn77wk86Exxupaleacb5vsec1nFZI9an22p-eBU8ZCNAYsZfYbk1l2LoFrj_OhUnvY9hqoT2_NFFeIwHh9VX9qWGMz3PvqsYyWhbMx2iKwpQ-decvf11kt5D9vLm079DqJXhQ_RzIZbGKKlz4h2OlxciSNC9riIVZQ7rkvWsAgal08nJ40MxBePss8ywf5y6OVE5I_5dqv6RVijSr_rTLRj3CY4QVajmRGyAEmphQF5zvP9yDzuQfDbElZvW6UarkwwLwrhHavqyQmHrSoPLDZCWC124m5bOPv2Hh3qO44FypcuJPz5w2UYUIkRCJg_1GklOvt5eko_OuSDLLhBHPHgHIDrQPT8BOb4-JkCU6310HlwWTobx8V3UEAwnPKCkRu79ao0Li9KCIpnMCht-foCmzwOXwZTl7VyrVibRJzAdxTmTTIu-Jl1qw_-XzRYssmgRqo0_jK-UDhuE6QbJOii07L_EH2UNRDaICtSP8zFnMjinFxZNcw4NxhRi7VWit1hMu0nAE5B4Lf_hh9JbkFiHWMhmiXKpsK6ZnuqP7mcy3UAWUIpPwa7o";

        return Http::withToken($token);
    }
}
