<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subscriptions_are_updated_if_needed()
    {
        $fakeResponse = "{\"statusCode\":200,\"message\":\"OK\",\"data\":[{\"id\":\"016969e3-e1d4-4cfc-a222-a6eb3a95c11e\",\"external_subscription_id\":\"4789\",\"reseller_id\":\"17a6eb2d-9a9d-45df-b976-aa22255b6887\",\"integration_id\":\"4cf4163f-848e-4047-846d-abf32d68d88a\",\"first_name\":\"Test\",\"last_name\":\"test\",\"email\":\"test@atomia.com\",\"product_id\":3,\"user_id\":\"025813e9-c3d0-41c3-9852-6257f07140bb\",\"external_user_id\":\"123456a\",\"state\":\"ACTIVATED\",\"provision_state\":\"ACTIVATED\",\"run_wizard\":0,\"created_at\":\"15-03-2020\",\"domains\":[{\"id\":113,\"domain_name\":\"t4.deva.demo.impress.website\",\"idn_domain_name\":\"t4.deva.demo.impress.website\",\"impress_domain_name\":\"016969e3e1d44cfca222a6eb3a95c11e.deva.static.impress.website\",\"dns_record\":[{\"type\":\"CNAME\",\"value\":\"_a768ffe50ae8272994c04f74ad2c5697.nhqijqilxf.acm-validations.aws.\",\"name\":\"_fb028dc865bb495109bfa0ce31ac0578.t4.deva.demo.impress.website.\",\"ttl\":\"1\"},{\"type\":\"CNAME\",\"value\":\"_a7c0a074a12000cf1ac69b61ddeba9e6.nhqijqilxf.acm-validations.aws.\",\"name\":\"_2ef3a34ac49996f21d39487d160d89e5.www.t4.deva.demo.impress.website.\",\"ttl\":\"1\"},{\"type\":\"ALIAS\",\"value\":\"016969e3e1d44cfca222a6eb3a95c11e.cdn.deva.static.impress.website.\",\"name\":\"t4.deva.demo.impress.website.\",\"ttl\":\"300\"},{\"type\":\"CNAME\",\"value\":\"016969e3e1d44cfca222a6eb3a95c11e.cdn.deva.static.impress.website.\",\"name\":\"www.t4.deva.demo.impress.website.\",\"ttl\":\"300\"}]}]}]}";

        $data = json_decode($fakeResponse)->data[0];

        $subscription = Subscription::create([
            'id' => $data->id,
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'email' => $data->email,
            'domain_name' => $data->domains[0]->domain_name,
            'state' => 'PURCHASED'
        ]);

        $this->assertEquals($subscription->state, 'PURCHASED');

        Subscription::updateStates($fakeResponse);

        $this->assertEquals($subscription->fresh()->state, 'ACTIVATED');
    }
}
