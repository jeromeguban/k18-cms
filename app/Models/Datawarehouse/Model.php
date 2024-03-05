<?php

namespace App\Models\Datawarehouse;

use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
	use Pagination;
 	protected $connection = 'datawarehouse';
 	protected $guarded = [];

 	public function scopeSelectedFields($query)
    {
    	return $query->select([ "{$this->getTable()}.*" ]);
    }
    

}