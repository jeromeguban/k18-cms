<?php

namespace App\Observers;

use App\Models\Posting;

use App\Models\Searchable\Posting as SearchablePosting;

class PostingObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
     public $afterCommit = true;

     
    /**
     * Handle the Posting "published" event.
     *
     * @param  \App\Models\Posting  $posting
     * @return void
     */
    public function updated(Posting $posting)
    {
        $method = 'searchable';

        if(!$posting->published_date)
            $method = 'unsearchable';

        SearchablePosting::where('posting_id', $posting->posting_id)->$method();  
    }


    /**
     * Handle the Posting "deleted" event.
     *
     * @param  \App\Models\Posting  $posting
     * @return void
     */
    public function deleted(Posting $posting)
    {
        SearchablePosting::where('posting_id', $posting->posting_id)->onlyTrashed()->unsearchable();  
    }
}
