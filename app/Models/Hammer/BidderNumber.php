<?php

namespace App\Models\Hammer;

use Illuminate\Database\Eloquent\SoftDeletes;

class BidderNumber extends Model
{
    use SoftDeletes;

    protected $primaryKey = "bidder_number_id";


    public static function getLastestBidderNumber($auction)
    {
        $bidder_number = self::where('auction_id', $auction)
                            ->orderBy('bidder_number', 'DESC')
                            ->first();
                            
        return $bidder_number ? ((int) $bidder_number->bidder_number + 1) : 1;
    }

}