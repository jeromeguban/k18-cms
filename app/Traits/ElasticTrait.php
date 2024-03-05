<?php

namespace App\Traits;

trait ElasticTrait
{
    public function models()
    {
    	return [
            'Searchable\Auction', 
    		'Searchable\Posting', 
            'Searchable\PostingItem', 
            'Searchable\Category', 
            'Searchable\SubCategory', 
            'Searchable\Store',
            'Searchable\Tag',
            'Searchable\Brand',
            'Searchable\PaymentType',
    	];
    }
}
