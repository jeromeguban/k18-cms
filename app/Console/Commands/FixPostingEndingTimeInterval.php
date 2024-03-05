<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Auction;
use App\Models\Posting;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Helpers\CacheHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class FixPostingEndingTimeInterval extends Command
{
    protected $auction, $posting_bar;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:posting-ending-time-interval {auction} {interval}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Interval';

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

        $this->auction = Auction::where('auction_id',$this->argument('auction'))->first();

        if($this->auction){
            
            $postings = Posting::wherePublished()
                ->where('auction_id', $this->auction->auction_id)
                ->orderByRaw("CHAR_LENGTH(lot_number) ASC, lot_number ASC")
                ->get();
      
            $this->posting_bar = new ProgressBar($this->output, count($postings));
            $this->posting_bar->setFormat("Processing Postings: %current%/%max% [%bar%] %remaining%");

            $this->posting_bar->start();

            DB::transaction(function() use($postings){

                foreach( $postings as $index => $posting ) {

                    $ending_time = Carbon::parse($this->auction->ending_time);
                    $ending_time->addSeconds( $index * $this->argument('interval'));
                    
                    $posting->update([
                        'starting_time'  => Carbon::parse($this->auction->starting_time)->toDateTimeString(),
                        'ending_time'    => $ending_time->toDateTimeString(),
                    ]);

                    SearchablePosting::where('posting_id', $posting->posting_id)->searchable();

                    (new CacheHelper)->setModelCache($posting);

                    $this->posting_bar->advance();
                }
           
            });
     
            $this->posting_bar->finish();
            print "\n";

        }
    }
}