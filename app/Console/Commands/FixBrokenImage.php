<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posting;
use App\Models\Searchable\Posting as SearchablePosting;
use Symfony\Component\Console\Helper\ProgressBar;

class FixBrokenImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:broken-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Broken Image';

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
        $postings = Posting::selectedFields()
                            // ->where('category', '=', 'Expression of Interest')
                            ->whereDate('ending_time', '>' , now()->toDateTimeString())
                            ->where('category', '=', 'Auction')
                            ->whereNotNull('published_date')
                            ->whereNotNull('images')
                            // ->where('posting_id', 99213)
                            ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($postings as $posting){
            $posting->update([
                'images'    => str_replace("2022/06/","2022/06",$posting->images)
            ]);

            SearchablePosting::where('posting_id',$posting->posting_id)
                            ->searchable();

            $bar->advance();
        }
        $bar->finish();
            print "\n";
         $this->comment('Successfully fixed broken image');
    }
}
