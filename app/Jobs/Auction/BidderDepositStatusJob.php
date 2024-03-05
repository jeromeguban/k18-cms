<?php

namespace App\Jobs\Auction;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BidderDeposit;
use App\Processes\Hammer\BidderDepositStatusProcess;

class BidderDepositStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bidder_deposit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BidderDeposit $bidder_deposit)
    {
        $this->bidder_deposit = $bidder_deposit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = new BidderDepositStatusProcess($this->bidder_deposit->toArray());
        $process->create();
    }
}
