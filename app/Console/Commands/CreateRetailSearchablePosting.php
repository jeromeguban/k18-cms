<?php

namespace App\Console\Commands;

use App\Models\Posting;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;


class CreateRetailSearchablePosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:searchable-posting {store}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Searchable Posting per Store';

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
        $postings   = Posting::where('postings.store_id', $this->argument('store'))
                        ->whereNotNull('postings.published_date')
                        ->where('postings.quantity', '>=', 1)
                        ->get();
    

        if(!$postings->count())
        
            return $this->comment('No Postings needed to create!');


        foreach($postings as $posting) {

         SearchablePosting::where('posting_id', $posting->posting_id)
            ->where('quantity','>=', 1)
            ->searchable();  

         SearchablePostingItem::where('posting_id', $posting->posting_id)
            ->where('quantity','>=', 1)
            ->searchable();
        
        }

        $this->comment('Searchable Posting Successfully Created!');
    
    }
}
