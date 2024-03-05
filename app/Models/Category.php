<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
     use SoftDeletes;

    protected $primaryKey = "category_id";

    protected $searchable_fields = [
        'category_name',
        'category_code'
    ];

    public function isExist()
    {
        $is_exist =  self::where('category_code', $this->category_code)
            ->first();

        return ($is_exist) ? true : false;
    }


    public function addSubCategory(SubCategory $sub_category)
    {
        if($sub_category->isExist()) {
            throw new \Exception('Sub Category already existed.');
        }

        return $this->subCategories()->save($sub_category);
    }

    // Relationships
    public function subCategories()
    {
    	return $this->hasMany(SubCategory::class, 'category_id', 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
