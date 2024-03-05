<?php

namespace App\Models;


class Auction extends Model
{
    protected $table = "auctions";

    protected $primaryKey = "auction_id";

    protected $searchable_fields = [
        'auctions.auction_number',
        'auctions.name',
        'auctions.location',
        'auctions.category',
        'auctions.description',
    ];


    public function scopeWhereQueryScopes($query)
    {
        if(request()->category){
            $query->where('auctions.category', request()->category);
        }

        if(request()->from && request()->to){
            $query->whereBetween('auctions.created_at',[request()->from, request()->to]);
        }

    }
}
