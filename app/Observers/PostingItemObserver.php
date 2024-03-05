<?php

namespace App\Observers;

use App\Models\PostingItem;

use App\Models\Searchable\PostingItem as SearchablePostingItem;


class PostingItemObserver
{
    /**
     * Handle the PostingItem "created" event.
     *
     * @param  \App\Models\PostingItem  $postingItem
     * @return void
     */
    public function created(PostingItem $postingItem)
    {
        //
    }

    /**
     * Handle the PostingItem "updated" event.
     *
     * @param  \App\Models\PostingItem  $postingItem
     * @return void
     */
    public function updated(PostingItem $postingItem)
    {
        $method = 'searchable';

        if(!$postingItem->published_date)
            $method = 'unsearchable';

        SearchablePostingItem::where('id', $postingItem->id)->$method();
    }

    /**
     * Handle the PostingItem "deleted" event.
     *
     * @param  \App\Models\PostingItem  $postingItem
     * @return void
     */
    public function deleted(PostingItem $postingItem)
    {
        SearchablePostingItem::where('id', $postingItem->id)->unsearchable();
    }

    /**
     * Handle the PostingItem "restored" event.
     *
     * @param  \App\Models\PostingItem  $postingItem
     * @return void
     */
    public function restored(PostingItem $postingItem)
    {
        //
    }

    /**
     * Handle the PostingItem "force deleted" event.
     *
     * @param  \App\Models\PostingItem  $postingItem
     * @return void
     */
    public function forceDeleted(PostingItem $postingItem)
    {
        //
    }
}
