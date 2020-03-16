<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['id', 'first_name', 'last_name', 'email', 'domain_name', 'state' ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected function updateStates($response)
    {
        $data = collect(json_decode($response)->data)->keyBy('id');

        self::where('state', 'PURCHASED')
            ->get()
            ->filter(fn($subscription) => $data[$subscription->id]->provision_state == 'ACTIVATED')
            ->each->update(['state' => 'ACTIVATED']);
    }
}
