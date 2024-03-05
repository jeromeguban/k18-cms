<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Posting;
use App\Models\PostingItem;
use App\Helpers\CacheHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class GenerateEventPostingCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:event-posting-cache {event}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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


        $event = null;
        if($this->argument('event') != 'ALL')
            $event = Event::where('event_id', $this->argument('event'))
                            ->first();

        if($event)
            (new CacheHelper)->setModelCache($event);

        $postings = Posting::wherePublished()
                        ->when($event, function($query) use ($event) {
                            $query->where('event_id', $event->event_id);
                        })
                        // ->when(!$auction, function($query) {
                        //     $query->whereIn('event_id',[
                        //         6396
                        //     ]);
                        // })
                        // ->orderByRaw("CHAR_LENGTH(lot_number) ASC, lot_number ASC")
                        // ->where(function($query) {
                        //     $query->whereNotYetFinished();
                        // })
                        ->get();

        $postings->each(function($posting){

            $posting_items = PostingItem::where('posting_id', $posting->posting_id)->get();


            foreach($posting_items as $index => $posting_item) {

                Redis::set('postings_'.$posting->posting_id.'_items_'.$posting_item->id, $posting_item->toJson());
                // (new CacheHelper)->setModelCache($posting_item);
            }


            $posting->items = $posting_items->toArray();

            (new CacheHelper)->setModelCache($posting);
        });

        $this->comment('Posting Cache successfully generated!');
    }
}
