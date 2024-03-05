<?php

namespace App\Models\Elastic;

use App\Models\Model;
use App\Traits\Elastic;

class Auction extends Model
{
    use Elastic;

    protected $primaryKey = "auction_id";

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'auctions';
    }
}
