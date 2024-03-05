<?php

namespace App\Jobs\Auction;

use App\Models\Posting;
use Illuminate\Bus\Queueable;
use App\Helpers\GuzzleRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PostingCancellationJob implements ShouldQueue
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
        // $this->onQueue('posting-cancellation-job');
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
            'json' => $this->form(),
        ];
        
        $request = new GuzzleRequest($form);
        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/posting-cancellation');
       
        if($response->getStatusCode() != 200){

            if($response->getStatusCode() == 403)
                return;

            abort($response->getStatusCode(), json_encode($request->data()));
            
        }

    }

    private function form(){    

        $data = [
            'posting'     =>  $this->posting->toArray(),
            'signature'   =>  sha1($this->posting->posting_id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        
        return $data;
    }
}