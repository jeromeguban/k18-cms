<?php

namespace App\Helpers;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Redis;
use Twilio\Exceptions\ConfigurationException;

Class Twilio
{   
    public function getIceServers() {
        if($ice_servers = Redis::connection('hmr-local-redis')->get('twilio-ice-server'))
            return json_decode($ice_servers);

        return $this->generateIceServers();
    }

    private function generateIceServers()
    {
        try {
            $client = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        } catch (ConfigurationException $e) {
            // You can `catch` the exception, and perform any recovery method of your choice
            abort(500, 'Something went wrong. Please try again later.');
        }
       

        $token = $client->tokens
                        ->create();

        Redis::connection('hmr-local-redis')->set('twilio-ice-server', 
                        json_encode($token->iceServers), 
                        "EX", $token->ttl //Expiry based on seconds
                    );
                    
        return $token->iceServers;
    }
}
