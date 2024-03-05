<?php

namespace App\Jobs;

use App\Models\Term;
use Illuminate\Bus\Queueable;
use App\Helpers\GuzzleRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TermCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $term;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
        // $this->onQueue('term-create-job');
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
        $response =  $request->post(env('HAMMER_TOKEN_URL').'/api/terms');
       
        if($response->getStatusCode() != 200){

            if($response->getStatusCode() == 403)
                return;

            abort($response->getStatusCode(), json_encode($request->data()));
            
        }

    }

    private function form(){    

        $data = [
            'term'        =>  $this->term->toArray(),
            'signature'   =>  sha1($this->term->id.'{'.env('API_SIGNATURE_KEY').'}')
        ];

        
        return $data;
    }
}