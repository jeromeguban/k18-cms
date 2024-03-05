<?php

namespace App\Console\Commands;

use App\Models\PostingItem;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class PublishPostingItemCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:posting-item-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Posting Item Cache';

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

        $posting_items = PostingItem::where('quantity','>=',1)->get();
        
        $bar = new ProgressBar($this->output, count($posting_items));

        $bar->setFormat("Processing postings: %current%/%max% [%bar%] %remaining%");

        $bar->start();      

        foreach($posting_items as $posting_item) {

            SearchablePostingItem::where('id', $posting_item->id)->searchable();

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Posting Item Cache Successfully Published!');
    }
}
