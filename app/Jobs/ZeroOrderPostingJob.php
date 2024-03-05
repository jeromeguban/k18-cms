<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Posting;
use App\Models\OrderPosting;
use App\Helpers\GuzzleRequest;
use App\Models\BidderNumber;

class ZeroOrderPostingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $posting;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Posting $posting)
    {
        $this->posting = $posting;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $form = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'    => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'json' => $this->posting->toArray(),
        ];

        // dd($form);
        $request = new GuzzleRequest($form);
        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/orders-zero-bid-amount');
       
        if($response->getStatusCode() != 200){

            if($response->getStatusCode() == 403)
                return;

            abort($response->getStatusCode(), json_encode($request->data()));
            
        }
    }

    // private function form()
    // {
        

    //     $bidder_number = BidderNumber::where('bidder_number_id', $this->posting->bidder_number_id)
    //                                 ->first();


    //     $data = [
    //         // 'order'     => $this->order->toArray(),
    //         'postings'  => $this->posting->toArray(),
    //         'bidder_number'  => $bidder_number ? $bidder_number->toArray() : null,
    //         // 'signature' => sha1($this->order->reference_code.'{'.env('API_SIGNATURE_KEY').'}')
    //     ];

    //     return $data;
    // }
}
