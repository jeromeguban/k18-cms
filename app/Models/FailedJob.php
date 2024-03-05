<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FailedJob extends Model
{
    use HasFactory;

    protected $table = "failed_jobs";

    protected $searchable_fields = [
        'uuid',
        'connection',
        'queue',
        'payload'
    ];

}
