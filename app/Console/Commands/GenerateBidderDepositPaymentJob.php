<?php

namespace App\Console\Commands;

use App\Jobs\Auction\BidderDepositPaymentJob;
use App\Models\BidderDeposit;
use Illuminate\Console\Command;

class GenerateBidderDepositPaymentJob extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bidder-deposit-payment-job {reference_code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Bidder Deposit Payment Job';

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
        $bidder_deposit = BidderDeposit::where('reference_code', $this->argument('reference_code'))
                                ->where('status', 'Deposit')
                                ->first();
        if(!$bidder_deposit)
            $this->comment('Bidder Deposit not found.');


        dispatch(new BidderDepositPaymentJob($bidder_deposit, true));
    }
}
