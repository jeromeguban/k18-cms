<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


class Api extends Model
{
    use SoftDeletes;

    protected $table = "apis";
}
