<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessRequestEmailTemplate extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeJoinAuction($query)
    {
        $query->addSelect([
            'auctions.auction_number',
            'auctions.slug'
        ]);

        $query->join('auctions', 'auctions.auction_id', '=', 'access_request_email_templates.auction_id');
    }


    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
