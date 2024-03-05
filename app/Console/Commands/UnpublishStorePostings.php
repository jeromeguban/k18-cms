<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\PostingTag;
use App\Models\Store;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use App\Models\Searchable\Posting as SearchablePosting;

class UnpublishStorePostings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unpublish:store-postings {store}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish Store Postings';

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
        $postings = Posting::where('category', 'Retail')
                        ->whereNotNull('published_date')
                        ->where('store_id',  $this->argument('store'))
                        ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();
        
        foreach($postings as $posting) {
            $posting->update([
                'published_date'  => null,
                'published_by'  => null,
            ]);

            PostingTag::where('posting_id', $posting->posting_id)->update(['published_date' => null]);

            SearchablePosting::where('posting_id', $posting->posting_id)->unsearchable();

            $posting_items = PostingItem::where('posting_items.posting_id', $posting->posting_id)
                                    ->whereNotNull('published_date')
                                    ->get();
            
            foreach($posting_items as $posting_item) {
                $posting_item->update([
                    'published_date'  => null,
                    'published_by'  => null,
                ]);

                SearchablePostingItem::where('posting_id', $posting_item->posting_id)->unsearchable();
            }
            $bar->advance();
            
        }
        $bar->finish();
    }
}
