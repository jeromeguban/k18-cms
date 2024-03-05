<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Searchable\Category as SearchableCategory;

class CategoryObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    
    /**
     * Handle the Category "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        SearchableCategory::where('category_id', $category->category_id)->searchable();     
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        SearchableCategory::where('category_id', $category->category_id)->searchable();     
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        SearchableCategory::where('category_id', $category->category_id)->onlyTrashed()->unsearchable();  
    }
}
