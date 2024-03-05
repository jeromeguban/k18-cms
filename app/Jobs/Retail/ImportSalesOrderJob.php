<?php

namespace App\Jobs\Retail;

use App\Helpers\GuzzleRequest;
use App\Jobs\Retail\Middleware\GenerateSession;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ImportSalesOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order, $access_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->onQueue('update-sales-order');

    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new GenerateSession];
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $access_token = Redis::get('CMS_EXTRIM_API_TOKEN');

       $request = new GuzzleRequest([
            'headers' => [
                'Authorization' => 'Bearer '.$access_token,        
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'json' =>[
                'order_id'     =>  $this->order->id,
            ]
       ]);

        $response =  $request->post('https://xv2.hmrphils.com/api/import-sales-order');
        
        if($response->getStatusCode() != 200){

            if($response->getStatusCode() == 405)
                return;
            
            abort($response->getStatusCode(), json_encode($request->data()));

        }
    }

}
