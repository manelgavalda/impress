<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->withoutExceptionHandling();

        $data = "{\"id\":\"a49bb814-d4a0-44c8-9676-a876546092eb\",\"external_subscription_id\":\"220\",\"reseller_id\":\"17a6eb2d-9a9d-45df-b976-aa22255b6887\",\"integration_id\":\"4cf4163f-848e-4047-846d-abf32d68d88a\",\"first_name\":\"Marija\",\"last_name\":\"Bogdanovic\",\"email\":\"marija.bogdanovic+220@atomia.com\",\"product_id\":4,\"user_id\":\"4da91c08-e09a-4bb5-8870-bc63160d4328\",\"external_user_id\":\"220\",\"state\":\"ACTIVATED\",\"provision_state\":\"ACTIVATED\",\"run_wizard\":1,\"created_at\":\"02-03-2020\",\"domains\":[{\"id\":92,\"domain_name\":\"220-deva.demo.impress.website\",\"idn_domain_name\":\"220-deva.demo.impress.website\",\"impress_domain_name\":\"a49bb814d4a044c89676a876546092eb.deva.static.impress.website\",\"dns_record\":[{\"type\":\"CNAME\",\"value\":\"_87f29b80074ff2bd39eca04ccd786bcd.vhzmpjdqfx.acm-validations.aws.\",\"name\":\"_dce11a1dfe050c9b4f2f3bd35c6089ea.220-deva.demo.impress.website.\",\"ttl\":\"1\"},{\"type\":\"CNAME\",\"value\":\"_c163ed19ef05b553cb6326285b0d52e1.vhzmpjdqfx.acm-validations.aws.\",\"name\":\"_fef0f00aaf89e14474ab50e4b0be0925.www.220-deva.demo.impress.website.\",\"ttl\":\"1\"},{\"type\":\"ALIAS\",\"value\":\"a49bb814d4a044c89676a876546092eb.cdn.deva.static.impress.website.\",\"name\":\"220-deva.demo.impress.website.\",\"ttl\":\"300\"},{\"type\":\"CNAME\",\"value\":\"a49bb814d4a044c89676a876546092eb.cdn.deva.static.impress.website.\",\"name\":\"www.220-deva.demo.impress.website.\",\"ttl\":\"300\"}]}]}";

        $data = json_decode($data);

        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'ID',
                'Name',
                'id' => $data->id,
                'name' => "{$data->first_name} {$data->last_name}",
            ]);
    }
}
