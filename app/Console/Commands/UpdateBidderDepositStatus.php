<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BidderDeposit;
use App\Jobs\Auction\BidderDepositStatusJob;

class UpdateBidderDepositStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:bidder-deposits {reference_code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $bidder_deposit = BidderDeposit::where('bidder_deposits.reference_code', $this->argument('reference_code'))
                                        ->firstOrFail();


        BidderDepositStatusJob::dispatchSync($bidder_deposit);
    }
}
