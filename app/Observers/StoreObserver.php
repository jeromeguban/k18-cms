<?php

namespace App\Observers;

use App\Models\Store;
use App\Models\Searchable\Store as SearchableStore;

class StoreObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    
    /**
     * Handle the Store "created" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function created(Store $store)
    {
        SearchableStore::where('id', $store->id)->searchable();  
    }

    /**
     * Handle the Store "updated" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function updated(Store $store)
    {
        SearchableStore::where('id', $store->id)->searchable();  
    }

    /**
     * Handle the Store "deleted" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function deleted(Store $store)
    {
        SearchableStore::where('id', $store->id)->onlyTrashed()->unsearchable();  
    }
}
