<?php

namespace App\Models;

use App\Traits\Pagination;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\Encryptable;

class Posting extends Model
{
    use SoftDeletes, Pagination, Encryptable;


    protected $appends = [
        'total_item',
        'total_published_item',
        'extended_desc',
        'desc'
    ];

    protected $encryptable = [
        'customer_firstname',
        'customer_lastname',
        'email',
        'mobile_no',
    ];

    protected $primaryKey = "posting_id";

    protected $searchable_fields = [
    	'name',
    	'description',
    	'extended_description',
        // 'affiliate_marketings.first_name',
        // 'affiliate_marketings.last_name',
        // 'affiliate_marketings.code',

    ];

    /**
    * Call Observer Publish Method
    *
    * @return response()
    */
    public function unpublished()
    {
        $this->fireModelEvent('unpublish', false);
    }

    public function scopeWhereQueryScopes($query)
    {

        if(request()->status && request()->status == 'publish'){
            $query->whereNotNull('postings.published_date');
        }

        if(request()->status && request()->status == 'unpublish'){
            $query->whereNull('postings.published_date');
        }

        if(request()->from && request()->to){
            $query->whereBetween(request()->filter_type ? 'postings.' . request()->filter_type : 'postings.created_at' ,[request()->from, request()->to]);
        }

    }

    public function scopeWherePublished($query)
    {
        return $query->whereNotNull('postings.published_date');
    }


    public function scopeWhereNotYetFinished($query)
    {
        return $query->where('postings.ending_time', '>', now()->toDateTimeString());
    }


    public function scopeWhereFinished($query)
    {
        return $query->where('postings.ending_time', '<', now()->toDateTimeString());
    }

    //Relation
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }


    //Join

     public function scopeJoinOrderPosting($query)
    {
        $query->addSelect([
                    'order_postings.*',
                ]);

        $query->join('order_postings', 'order_postings.posting_id', '=', 'postings.posting_id');
    }

    public function scopeJoinOrder($query)
    {
        $query->addSelect([
                    'orders.*',
                ]);

        $query->join('orders', 'orders.id', '=', 'order_postings.order_id');
    }

    public function getTotalItemAttribute()
    {
        $item_count = PostingItem::select([
                    \DB::raw('count(id) AS count')
                ])
                ->where('posting_items.posting_id', $this->posting_id)
                ->groupBy([
                    'id'
                ])
                ->first();

            if(!$item_count)
                return [
                    'count'   =>   0
                ];

            return $item_count;
    }


    public function getTotalPublishedItemAttribute()
    {
        $published_count = PostingItem::select([
                    \DB::raw('if(count(id), count(id) , 0) AS published_count')
                ])
                ->where('posting_items.posting_id', $this->posting_id)
                ->whereNotNull('published_date')
                ->groupBy([
                    'id'
                ])
                ->first();


        if(!$published_count)
            return [
                'published_count'   =>   0
            ];

        return $published_count;

    }

    public static function getLastestPostingSequence($event)
    {   
        $posting = self::where('event_id', $event)
                            ->orderBy('sequence', 'DESC')
                            ->first();

        return $posting ? ((int) $posting->sequence + 1) : 1;
    }

    public function getExtendedDescAttribute()
    {
        // $posting = Posting::select([
        //             \DB::raw('extended_description')
        //         ])
        //         ->where('posting_id', $this->posting_id)
        //         ->first();

        //         $ext_desc =  strip_tags($posting->extended_description);


        return strip_tags($this->extended_description);

    }

    public function getDescAttribute()
    {
        // $posting = Posting::select([
        //             \DB::raw('extended_description')
        //         ])
        //         ->where('posting_id', $this->posting_id)
        //         ->first();

        //         $ext_desc =  strip_tags($posting->extended_description);


        return strip_tags($this->description);

    }

}
