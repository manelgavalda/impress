<?php

namespace Tests\Feature;

use DB;
use App\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->id = "9dec3458-af96-48f7-bca7-87b08e5d4d22";

        $this->accessToken = str_repeat("e", 1208);

        $this->responses = [
            'token' =>  [
                "token_type" => "Bearer",
                "expires_in" => 31536000,
                "access_token" => $this->accessToken
            ],
            'new_subscription' => [
                "statusCode" => 202,
                "message" => "Accepted",
                "data" => [
                    "id" => $this->id,
                    "external_subscription_id" => "2",
                    "reseller_id" => "17a6eb2d-9a9d-45df-b976-aa22255b6887",
                    "integration_id" => "4cf4163f-848e-4047-846d-abf32d68d88a",
                    "first_name" => "test",
                    "last_name" => "test",
                    "email" => "test@atomia.com",
                    "product_id" => "3",
                    "user_id" => "2",
                    "external_user_id" => "1",
                    "state" => "ACTIVATED",
                    "provision_state" => "PURCHASED",
                    "run_wizard" => null,
                    "created_at" => "17-03-2020",
                    "domains" => [
                        0 => [
                            "id" => 207,
                            "domain_name" => "test-deva.demo.impress.website",
                            "idn_domain_name" => "test-deva.demo.impress.website",
                            "impress_domain_name" => null,
                            "dns_record" => []
                        ]
                    ]
                ]
            ],
            'subscriptions' => [
                "statusCode" => 200,
                "message" => "OK",
                "data" => [
                    0 => [
                        "id" => $this->id,
                        "external_subscription_id" => "2",
                        "reseller_id" => "17a6eb2d-9a9d-45df-b976-aa22255b6887",
                        "integration_id" => "4cf4163f-848e-4047-846d-abf32d68d88a",
                        "first_name" => "test",
                        "last_name" => "test",
                        "email" => "test@atomia.com",
                        "product_id" => "3",
                        "user_id" => "2",
                        "external_user_id" => "1",
                        "state" => "ACTIVATED",
                        "provision_state" => "ACTIVATED",
                        "run_wizard" => null,
                        "created_at" => "17-03-2020"
                    ]
                ]
            ]
        ];
    }

    /** @test */
    public function can_generate_and_save_api_tokens()
    {
        Http::fake([
            config('impress.url') . '/oauth/token' => Http::response($this->responses['token'])
        ]);

        $this->get('subscription');

        $this->assertDatabaseHas('tokens', [
            'api_token' => $this->accessToken
        ]);
    }

    /** @test */
    public function can_store_subscriptions_and_update_them_if_necessary()
    {
        Http::fake([
            config('impress.url') . '/oauth/token' => Http::response($this->responses['token']),
            config('impress.url') . '/api/subscription' => Http::sequence()
                ->push($this->responses['new_subscription'])
                ->push($this->responses['subscriptions'])
        ]);

        $this->assertEquals(0, Subscription::count());

        $this->post('subscription', [
            'first_name' => 'test',
            'second_name' => 'test',
            'domain_name' => 'test',
            'external_user_id' => '1',
            'external_subscription_id' => '2',
        ])->assertRedirect('/subscription');

        $this->assertEquals(1, Subscription::count());

        $subscription = Subscription::find($this->id);

        $this->assertEquals($subscription->state, 'PURCHASED');

        $this->get('subscription')
            ->assertOk()
            ->assertSeeInOrder([
                'test test',
                'test@atomia.com',
                'test-deva.demo.impress.website',
                'ACTIVATED'
            ]);

        $this->assertEquals($subscription->fresh()->state, 'ACTIVATED');
    }
}
