<?php

namespace App\Console\Commands;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 14400);

use App\Models\Posting;
use App\Models\Tag;
use App\Models\PostingTag;
use App\Models\Searchable\Posting as SearchablePosting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Support\Facades\Cache;

class GeneratePostingTags extends Command
{
    protected $posting, $postings;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:posting-tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Posting Tag';

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


        $this->postings = Posting::whereRaw('postings.suggested_retail_price > 0')
            ->whereRaw('postings.suggested_retail_price < postings.unit_price')
            ->where('category', 'Retail')
            ->whereNull('deleted_at')
            ->whereNull('auction_id')
            ->get();

        $bar = new ProgressBar($this->output, count($this->postings));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach ($this->postings as $posting) {

            \DB::transaction(function () use ($posting) {

                try {
                    $this->createPostingTag($posting);
                    SearchablePosting::where('posting_id', $posting->posting_id)->searchable();
                } catch (\Exception $e) {
                }
            });

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Posting Tags Successfully Generated!');
    }


    private function createPostingTag($posting)
    {

        $this->posting = Posting::where('posting_id', $posting->posting_id)->first();

        $set_tag[] = 4;

        $tags  = json_decode($this->posting->tags);

        $new_tag = array_unique(array_merge($tags, $set_tag));

        $count = count(json_decode($this->posting->refresh()->tags));

        if ($count >= 1) {

            $this->posting->update([
                'tags' => json_encode($new_tag),
            ]);

            $updated_tags = json_decode($this->posting->tags);

            foreach ($updated_tags as $tag) {



                Cache::lock("tag-{$tag}-update-or-create")->get(function () use ($tag) {

                    PostingTag::updateOrCreate([

                        'posting_id' => $this->posting->posting_id,
                        'tag_id'     => $tag,

                    ], [
                        'posting_id' => $this->posting->posting_id,
                        'tag_id'     => $tag,
                        'created_by' => 1,
                        'published_date' => $this->posting->published_date,
                    ]);
                });
            }
        }
    }
}
