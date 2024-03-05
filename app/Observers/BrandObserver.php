<?php

namespace App\Observers;

use App\Models\Brand;
use App\Models\Searchable\Brand as SearchableBrand;

class BrandObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;


    /**
     * Handle the Brand "created" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function created(Brand $brand)
    {   
     
        SearchableBrand::where('brand_id', $brand->brand_id)->searchable();     
    }

    /**
     * Handle the Brand "updated" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function updated(Brand $brand)
    {
        SearchableBrand::where('brand_id', $brand->brand_id)->searchable();  
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function deleted(Brand $brand)
    {

        SearchableBrand::where('brand_id', $brand->brand_id)->onlyTrashed()->unsearchable();  
    }
}
