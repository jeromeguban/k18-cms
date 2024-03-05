<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'desc',
        'salarys',
        'locations'
    ];

    protected $primaryKey = "career_id";




    //Mutators
    protected function getLocationsAttribute()
    {
        return json_decode($this->work_location);
    }

    protected function getSalarysAttribute()
    {
        return json_decode($this->monthly_salary);
    }

    protected function getRequirementsAttribute()
    {
        return json_decode($this->job_requirements);
    }

    protected function getDescAttribute()
    {
        return strip_tags($this->job_description);
    }
}
