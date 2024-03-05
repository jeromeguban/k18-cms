<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PostingItem;
use App\Models\OrderPosting;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateRetailOrderPostingDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:retail-order-posting-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Retail Order Posting Details';

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
        $order_postings = OrderPosting::where('category','Retail')
                                    ->where('created_at', 'LIKE', '%2023-09-20%')
                                    ->get();

        $posting_item_ids = $order_postings->pluck('posting_item_id')
                                ->toArray();

        $posting_items = PostingItem::whereIn('id', $posting_item_ids)
                        // ->whereNotNull('published_date')
                        ->get();

        $bar = new ProgressBar($this->output, count($order_postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($order_postings as $index => $order_posting) {

            $posting_item = $posting_items->where('posting_id', $order_posting->posting_id)
                            ->first();
            if(!$posting_item)
                continue;

            $order_posting->update([
                'details'   => $posting_item->toJson()
            ]);

            $bar->advance();

        }

        $bar->finish();
    }
}
