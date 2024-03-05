<?php

namespace App\Models\Address;

use App\Models\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zipcode extends Model
{
    protected $connection = 'address';
}
