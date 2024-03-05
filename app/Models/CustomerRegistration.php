<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;

class CustomerRegistration extends Model
{
    use HasFactory, Encryptable;

    protected $primaryKey = "id";


    protected $searchable_fields = [
        'customer_firstname_index',
        'customer_lastname_index',
        'email_index',
        'mobile_no_index',
    ];


    protected $encryptable = [
        'customer_firstname',
        'customer_lastname',
        'customer_middlename',
        'customer_suffixname',
        'mobile_no',
        'email',
        'username',

    ];
}
