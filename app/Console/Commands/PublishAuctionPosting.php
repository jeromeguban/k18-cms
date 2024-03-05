<?php

namespace App\Console\Commands;

use App\Models\Searchable\Posting as SearchablePosting;
use Illuminate\Console\Command;

class PublishAuctionPosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:auction-posting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Published Auction Posting';

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

        SearchablePosting::whereNotNull('published_date')
            ->whereNull('deleted_at')
            ->whereDate('ending_time', '>', now()->toDateTimeString())
            ->where('category', 'Auction')
            ->searchable();

        $this->comment('Auction Posting Successfully Published!');
    }
}
