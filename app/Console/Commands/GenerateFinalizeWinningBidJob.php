<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Auction;
use App\Models\Posting;
use App\Models\BidHistory;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Helpers\GuzzleRequest;
use App\Jobs\CheckIfBuyBackJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Auction\FinalizeWinningBidJob;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateFinalizeWinningBidJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:finalize-winning-bid-job {auction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute FinalizeWinningBidJob to a specific Auction.';
    protected $posting_bar;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
{
       
        $postings   = Posting::whereNotNull('published_date')
                        ->where('category', 'Auction')
                        ->where('auction_category','!=','Simulcast')
                        ->where('auction_id', $this->argument('auction'))
                        ->whereNotNull('finalized_date')
                        ->get();
    

        if(!$postings->count())
            return $this->comment('No Postings needed to be tallied!');

        // $lot_ids = $postings->pluck('lot_id')
        //              ->toJson();

        // $item_ids = $postings->pluck('item_id')
        //              ->toJson();
        

        // $request = new Guzzle([
        //     'headers' => [
        //         'Content-Type'  => 'application/json'
        //     ],
        //     'form_params' => [
        //         'lots'  => $lot_ids,
        //         'items' => $item_ids
        //     ]
        // ]);

        // $response = $request->post(env('HAMMER_TOKEN_URL').'/api/api/buy-back');

        // if($response->getStatusCode() != 200)
        //      abort($response->getStatusCode(), json_encode($request->data()));

        //  $vendor_customers = collect($request->data());

         

        $this->posting_bar = new ProgressBar($this->output, count($postings));

        $this->posting_bar->setFormat("Processing postings: %current%/%max% [%bar%] %remaining%");

        $this->posting_bar->start();

        $postings->each(function($posting) {

            \DB::transaction(function() use ($posting) {
                try {

                    // $this->generateBidHistory($posting->refresh());

                    \Artisan::call('generate:tallied-bid-history', ['posting' => $posting->posting_id]);

                    $bid_amount         = Redis::get('postings_'.$posting->posting_id.'_bid_amount');
                    $customer_id        = Redis::get('postings_'.$posting->posting_id.'_customer_id');
                    $bidder_number_id   = !empty(Redis::get('postings_'.$posting->posting_id.'_bidder_number_id')) ? Redis::get('postings_'.$posting->posting_id.'_bidder_number_id') :null ;

                    $posting_updatable_columns = [];

                    if( ( 
                        (float) $bid_amount != (float) $posting->bid_amount
                            || $customer_id != $posting->customer_id
                            || $bidder_number_id != $posting->bidder_number_id
                        ) 
                        && (float) $bid_amount > 0) {
                        
                        BidHistory::updateOrCreate([ 
                            'bid_amount'        => (float)$bid_amount,
                            'posting_id'        => $posting->posting_id, 
                        ],[
                            'bidder_number_id'  => $bidder_number_id, 
                            'customer_id'       => $customer_id, 
                        ]);

                        $posting_updatable_columns['bid_amount'] = $bid_amount;
                        $posting_updatable_columns['customer_id'] = $customer_id;
                        $posting_updatable_columns['bidder_number_id'] = $bidder_number_id;
                        

                    }


                    $for_approval = $posting->for_approval ? 1 : ( (float)$bid_amount < (float)$posting->reserved_price  ? 1 : 0);

                    // $posting_customers = $vendor_customers->filter(function($vendor_customer) use ($posting){

                    //     return $vendor_customer['posting_id']  == $posting->posting_id;
                    // });

                    
                    // $buy_back = $posting_customers && count($posting_customers['vendor_customers']) 
                    //                     && in_array($customer_id, $posting_customers['vendor_customers']) 
                    //                     ? 1 : 0;

                    $posting->update(array_merge($posting_updatable_columns, [
                        'buy_back'           => 0,
                        // 'buy_back'           => $buy_back,
                        'for_approval'       => $for_approval ? 1 : 0,
                        'processing_fee'     => $posting->vat > 0 || $posting->duties > 0 ? (float)$bid_amount * (665/10000) : 0,
                        'for_approval_status'=> !$for_approval ? "Approved" : null,
                        'approved_date'      => !$for_approval ? now()->toDateTimeString() : null,
                        'finalized_date'     => now()->toDateTimeString()
                    ]));

                    
                    SearchablePosting::where('posting_id', $posting->posting_id)->searchable();
                    dispatch(new FinalizeWinningBidJob($posting->refresh()));

                } catch (\Exception $e) {
                    echo $e->getMessage();
                } 
            });

            $this->posting_bar->advance();
            
        });

        $this->posting_bar->finish();
        print "\n";

        return $this->comment('Jobs successfuly sent!');
    }

    private function getTotalAmount($posting)
    {
        return  (float) $posting->bid_amount + 
                (float) $posting->processing_fee +
                (float) $posting->notarial_fee +
                (float) $posting->other_fee +
                ((float)$posting->bid_amount * (float)($posting->buyers_premium /100)) + 
                ((float)$posting->bid_amount * (float)($posting->vat /100)) + 
                ((float)$posting->bid_amount * (float)($posting->duties /100)); 
    }

    private function generateBidHistory($posting)
    {
        $bid_histories = Redis::get("postings_".$posting->posting_id."_bid_histories");

        $bid_histories = json_decode($bid_histories,true);

        foreach ($bid_histories as $bid_history) {
            BidHistory::updateOrCreate([ 
                'bid_amount'        => $bid_history['bid_amount'],
                'posting_id'        => $posting->posting_id, 
            ],[
                'bidder_number_id'  => $bid_history['bidder_number_id'], 
                'customer_id'       => $bid_history['customer_id'], 
                'server_time'       => $bid_history['server_time'],
                'max_bid'           => $bid_history['max_bid'],
            ]);
        }
    }


    // private function checkIfBuyBack($posting)
    // {
       
    //     $form = [

    //         'form_params' => $this->form($posting),
    //     ];
        
    //     $request = new GuzzleRequest($form);
    //     $response =  $request->get(env('HAMMER_TOKEN_URL').'/api/buy-back');
       
    //     if($response->getStatusCode() != 200)
    //     abort($response->getStatusCode(), json_encode($request->data()));

    //     $posting->update(['buy_back' =>  $request->data()['data']]);
    // }

    // private function form($posting){


    //     $data = [
    //         'posting'   =>  $posting->toArray(),
    //     ];
        
    //     return $data;
    // }
}
