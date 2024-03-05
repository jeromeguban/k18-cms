<?php

namespace App\Console\Commands;

use App\Models\Searchable\Posting as SearchablePosting;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UnpublishAuctionPosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unpublish:auction-posting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upublished Auction Posting';

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
        $expDate = Carbon::now()->subDays(4)->toDateTimeString();
        $from = Carbon::now()->subDays(30)->toDateTimeString();
        $to = Carbon::now()->addDays(5)->toDateTimeString();

        // SearchablePosting::whereDate('ending_time', '<', now()->toDateTimeString())
        //     ->where('category', 'Auction')
        //     ->whereNull('item_category_type')
        //     ->unsearchable();

        SearchablePosting::whereDate('payment_period', '<', $expDate)
            ->where('category', 'Auction')
            ->whereNull('item_category_type')
            ->unsearchable();

        SearchablePosting::onlyTrashed()
            ->where('category', 'Auction')
            ->whereBetween('created_at', [$from, $to])
            ->unsearchable();

        $this->comment('Posting Successfully Unpublished!');
    }
}
