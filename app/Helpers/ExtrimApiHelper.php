<?php

namespace App\Helpers;

use App\Helpers\GuzzleRequest;
use Illuminate\Support\Facades\Redis;


class ExtrimApiHelper 
{

    protected $params;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(array $params = [])
    {
        $this->params = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'json' => [
                'username'      =>  'dev',
                'password'      =>  'pass9352',
            ]
        ];
      
        if(!empty($params)){

            $this->params = $params;
        }

    }

    public function retrieveToken() 
    {
        if($token = Redis::get('CMS_EXTRIM_API_TOKEN'))
            return $token;

        return $this->generateToken(); 
    }

    public function generateToken()
    {
        $request = new GuzzleRequest($this->params);
        $response = $request->post('https://xv2.hmrphils.com/auth');
    
        if($response->getStatusCode() != 200 )
            abort($response->getStatusCode(), 'Authentication Error. Please try again later.');

        Redis::set('CMS_EXTRIM_API_TOKEN', $request->data()['access_token']);

        return $request->data()['access_token'];
    }

}
