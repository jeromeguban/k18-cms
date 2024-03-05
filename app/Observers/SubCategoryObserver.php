<?php

namespace App\Observers;

use App\Models\SubCategory;
use App\Models\Searchable\SubCategory as SearchableSubCategory;

class SubCategoryObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    
    /**
     * Handle the SubCategory "created" event.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return void
     */
    public function created(SubCategory $subCategory)
    {
        SearchableSubCategory::where('sub_category_id', $subCategory->sub_category_id)->searchable();  
    }

    /**
     * Handle the SubCategory "updated" event.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return void
     */
    public function updated(SubCategory $subCategory)
    {
        SearchableSubCategory::where('sub_category_id', $subCategory->sub_category_id)->searchable();  
    }

    /**
     * Handle the SubCategory "deleted" event.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return void
     */
    public function deleted(SubCategory $subCategory)
    {
        SearchableSubCategory::where('sub_category_id', $subCategory->sub_category_id)->onlyTrashed()->unsearchable(); 
    }

}
