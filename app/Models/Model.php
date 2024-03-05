<?php

namespace App\Models;
use DateTimeInterface;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
	use Pagination;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
	protected $guarded = [];
    protected $searchable_fields = [];
    protected $hidden = [
        'deleted_at',
        'deleted_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setSearchableFields(array $searchable_fields)
    {
    	$this->searchable_fields = $searchable_fields;
    	return $this;
    }

    public function scopeSelectedFields($query)
    {
    	return $query->select([ "{$this->getTable()}.*" ]);
    }

    public function getEncryptable() {
        return $this->encryptable;
    } 
}