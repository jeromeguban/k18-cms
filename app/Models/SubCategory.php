<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
	use SoftDeletes;

    protected $primaryKey = "sub_category_id";

    protected $searchable_fields = [
        'sub_category_name',
        'sub_category_code',
        'categories.category_code',
        'categories.category_name'
    ];


    public function scopeJoinCategory($query)
    {
        $query->addSelect([
            'categories.category_code',
            'categories.category_name',
        ]);


        $query->join('categories', 'sub_categories.category_id', '=', 'categories.category_id');

        return $query;
    }

     public function isExist()
    {
        $is_exist =  self::where('category_id', $this->category_id)
            ->where('sub_category_code', $this->sub_category_code)
            ->first();

        return ($is_exist) ? true : false;
    }

    // Relationships
    public function category()
    {
    	return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function item_master()
    {
    	return $this->hasMany(ItemMaster::class, 'sub_category_id', 'sub_category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'sub_category_created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'sub_category_modified_by');
    }
}
