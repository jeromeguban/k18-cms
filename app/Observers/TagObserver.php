<?php

namespace App\Observers;

use App\Models\Tag;
use App\Models\Searchable\Tag as SearchableTag;

class TagObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;


    /**
     * Handle the Tag "created" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function created(Tag $tag)
    {   
        SearchableTag::where('id', $tag->id)->searchable();     
    }

    /**
     * Handle the Tag "updated" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        SearchableTag::where('id', $tag->id)->searchable();  
    }

    /**
     * Handle the Tag "deleted" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {

        SearchableTag::where('id', $tag->id)->onlyTrashed()->unsearchable();  
    }
}
