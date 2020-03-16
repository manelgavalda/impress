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

    /**
    * To be a good test I will need to create it, check if exists and delete it,
    * but it takes too long to be able to delete it after creating it.
    * @test
    */
    public function can_create_a_subscription()
    {
        $this->get('subscription')
            ->assertDontSee('Another Subscription');

        $r$this->post('subscription', [
            "first_name" => 'Another',
            "last_name" => 'Subscription',
            "email" => 'ramonzampon@atomia.com',
            "domain_name" => '1112',
            "external_user_id" => 27,
            "external_subscription_id" => 27
        ])->assertRedirect('subscription');

        $this->get('subscription')
            ->assertSee('Another Subscription');
    }
}
