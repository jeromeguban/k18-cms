<?php

namespace App\Jobs;


use App\Models\Posting;
use Illuminate\Bus\Queueable;
use App\Helpers\GuzzleRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class CheckIfBuyBackJob implements ShouldQueue
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

            'form_params' => $this->form(),
        ];
        
        $request = new GuzzleRequest($form);
        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/winning-bids/');
       
        if($response->getStatusCode() != 200)
            abort($response->getStatusCode(), json_encode($request->data()));

        $this->posting->update(['buy_back' =>  $request->data()['data']]);

    }

    private function form(){

            
       
        $data = [
            'posting'   =>  $this->posting->toArray(),
        ];
        
        return $data;
    }
}