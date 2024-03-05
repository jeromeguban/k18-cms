<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posting;

class RemoveDuplicatePosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:duplicate-posting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Duplicate Posting';

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
            $duplicates = Posting::select([
                                    \DB::raw('count(lot_id) as total_duplicate'),
                                    'lot_id'
                                ])
                                ->groupBy(['lot_id'])
                                ->having('total_duplicate', '>', 1)
                                ->whereNotNull('lot_id')
                                ->whereNotNull('auction_id')
                                ->where('category', 'Auction')
                                ->get();


            foreach($duplicates as $duplicate) {
                $posting = Posting::where('lot_id', $duplicate->lot_id)
                                ->orderBy('posting_id', 'DESC')
                                ->first();

                Posting::where('lot_id', $duplicate->lot_id)
                        ->where('posting_id', '!=', $posting->posting_id)
                        ->delete();
            }
    }
}
