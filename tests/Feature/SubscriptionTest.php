<?php

namespace Tests\Feature;

use DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_generate_and_save_api_tokens()
    {
        $this->get('subscription');

        $token = DB::table('tokens')->latest()->first()->api_token;

        $this->assertEquals(1208, strlen($token));
        $this->assertDatabaseHas('tokens', [
            'api_token' => $token
        ]);
    }

    /** @test */
    public function can_retrieve_all_subscriptions()
    {
        $this->get('subscription')
            ->assertOk()
            ->assertSee('Ramon Zampon');
    }

    /** @test */
    public function can_create_a_subscription()
    {
        $this->get('subscription')
            ->assertOk()
            ->assertDontSee('Another Subscription');

        $this->post('subscription', [
            "first_name" => 'Another',
            "last_name" => 'Subscription',
            "email" => 'ramonzampon@atomia.com',
            "domain_name" => '1112',
            "external_user_id" => 27,
            "external_subscription_id" => 27,
            "product_id" => 3,
            "state" => 'ACTIVATED'
        ])->assertRedirect('subscription');

        $this->get('subscription')
            ->assertOk()
            ->assertSee('Another Subscription');
    }
}
