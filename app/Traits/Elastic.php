<?php

namespace App\Traits;

use App\Elasticsearch\ElasticModel;
use ElasticScoutDriverPlus\Searchable;

trait Elastic
{
    use Searchable;
    
    public static function elastic()
    {
        return new ElasticModel(new static());
    }
}
