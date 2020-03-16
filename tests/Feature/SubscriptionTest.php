<?php

namespace Tests\Feature;

use DB;
use Tests\TestCase;
use App\Subscription;
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
    public function can_see_subscriptions()
    {
        Subscription::create([
            'id' => 'wqe2',
            'first_name' => 'Manel',
            'last_name' => 'Test',
            'email' => 'manel@atomia.com',
            'domain_name' => 'testdomain-deva.demo.impress.website',
            'state' => 'ACTIVATED'
        ]);

        $this->get('subscription')
            ->assertSee('Manel Test');
    }
}
