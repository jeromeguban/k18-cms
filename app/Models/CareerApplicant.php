<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;

class CareerApplicant extends Model
{
    use HasFactory,Encryptable;

    protected $primaryKey = "career_applicant_id";

    protected $encryptable = [
        'career_applicant_firstname',
        'career_applicant_lastname',
        'career_applicant_middlename',
        'mobile_no',
        'email'
    ];
}
