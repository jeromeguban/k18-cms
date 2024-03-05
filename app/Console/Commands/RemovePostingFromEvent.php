<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Posting;
use App\Models\Searchable\Posting as SearchablePosting;
use Symfony\Component\Console\Helper\ProgressBar;

class RemovePostingFromEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:posting-from-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Posting From Event and set it to public';

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
        $postings = Posting::where('event_id', 21)
                        ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        if($postings) {
            foreach($postings as $posting) {
                $posting->update([
                    'event_id' => null,
                    'type'  => 'Public',
                    'starting_time' => null,
                ]);

                $bar->advance();

                SearchablePosting::where('posting_id', $posting->posting_id)->searchable();
            }

            $bar->finish();
        }
    }
}
