<?php

namespace App\Console\Commands;

use App\Models\Posting;
use App\Models\PostingItem;
use App\Models\Searchable\Posting as SearchablePosting;
use App\Models\Searchable\PostingItem as SearchablePostingItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class UnpublishZeroQuantityPosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unpublish:zero-quantity-posting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upublished Zero Quantity Postings';

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

        $postings = Posting::where('postings.quantity', 0)
            ->whereNotNull('postings.published_date')
            ->where('postings.category', 'Retail')
            ->get();

        $bar = new ProgressBar($this->output, count($postings));

        $bar->setFormat("Processing postings: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach ($postings as $posting) {

            $posting_items = PostingItem::where('posting_items.posting_id', $posting->posting_id)->get();

            if ($posting_items) {
                foreach ($posting_items as $posting_item) {

                    if ((int) $posting_item->quantity <= 0) {
                        $posting_item->update([
                            'published_date' => null,
                            'published_by' => null,
                        ]);
                        SearchablePostingItem::where('posting_items.id', $posting_item->id)->unsearchable();
                    }
                }
            }

            $posting_quantity = PostingItem::select(DB::raw('SUM(quantity) AS total'))
                ->where('posting_items.posting_id', $posting->posting_id)
                ->groupBy('posting_items.posting_id')
                ->get();

            if ($posting_quantity) {

                if ((int) $posting_quantity[0]->total <= 0) {
                    $posting->update([
                        'published_date' => null,
                        'published_by' => null,
                    ]);
                    SearchablePosting::where('posting_id', $posting->posting_id)->unsearchable();
                }

                if ((int) $posting_quantity[0]->total >= 1) {
                    $posting->update([
                        'quantity' => (int) $posting_quantity[0]->total,
                    ]);
                    SearchablePosting::where('posting_id', $posting->posting_id)->searchable();
                }

            }

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Posting Successfully Unpublished!');
    }
}
