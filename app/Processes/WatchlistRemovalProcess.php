<?php

namespace App\Processes;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

use App\Models\Posting;
use App\Models\Watchlist;
use Illuminate\Support\Facades\DB;

class WatchlistRemovalProcess
{

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute handle process.
     *
     * @return void
     */

    public function handle()
    {
        DB::transaction(function () {
            $this->removeWatchlists();
        });
    }

    private function removeWatchlists()
    {

        $postings = Posting::select([
            'postings.posting_id',
        ])
            ->whereFinished()
            ->get();

        foreach ($postings as $posting) {

            Watchlist::where('posting_id', $posting->posting_id)->delete();

        }
    }
}
