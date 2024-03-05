<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidHistory extends Model
{
	use Encryptable, SoftDeletes;

    protected $searchable_fields = [
        'customers.customer_firstname_index',
        'customers.customer_lastname_index',
        'postings.description',
        'bidder_numbers.bidder_number',
    ];

    protected $encryptable = [
        'customer_firstname',
        'customer_lastname',
    ];


    public function posting()
     {
     	return $this->belongsTo(Posting::class, 'posting_id', 'posting_id');
     }

     public function bidderNumber()
    {
        return $this->belongsTo(BidderNumber::class, 'bidder_number_id', 'bidder_number_id');
    }

    public function scopeJoinBidderNumber($query)
    {
        $query->addSelect([
            'bidder_numbers.bidder_number',
        ]);

        $query->join('bidder_numbers', 'bid_histories.bidder_number_id', '=', 'bidder_numbers.bidder_number_id');

        return $query;
    }
}
