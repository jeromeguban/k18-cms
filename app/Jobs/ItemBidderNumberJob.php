<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Posting;
use App\Processes\Hammer\ItemBidderNumberProcess;

class ItemBidderNumberJob implements ShouldQueue
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
        // $this->onQueue('item-bidder-number-job');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = new ItemBidderNumberProcess($this->posting->toArray());
        $process->create();
    }
}
