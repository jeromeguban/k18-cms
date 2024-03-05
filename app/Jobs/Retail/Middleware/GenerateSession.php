<?php
 
namespace App\Jobs\Retail\Middleware;
 
use App\Helpers\GuzzleRequest;
use Illuminate\Support\Facades\Redis;
 
class GenerateSession
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
    	$token = Redis::get('CMS_EXTRIM_API_TOKEN');

    	if($token) {
    		$next($job);
    		return;
    	}

    	$request = new GuzzleRequest([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'json' => [
                'username'      =>  'webservice',
                'password'      =>  'N@mr@c#01',
            ]
        ]);

        $response = $request->post('https://xv2.hmrphils.com/auth');
    
        if($response->getStatusCode() != 200 )
            abort($response->getStatusCode(), 'Authentication Error. Please try again later.');

        Redis::set('CMS_EXTRIM_API_TOKEN', $request->data()['access_token'], 'EX', 900);

        $next($job);

    }
}