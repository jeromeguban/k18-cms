<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Customer;

class CustomerActivityLogController extends Controller
{
    private static $customer_model = 'App\Models\Customer';

    /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
       $this->middleware(['auth']);
   }

   public function index(Customer $customer)
   {
       $query = ActivityLog::selectedFields()
                           ->joinCreatedBy()
                           ->where('subject_id', $customer->customer_id)
                           ->where('subject_type', self::$customer_model)
                           ->orderBy('activity_log.created_at', 'desc');

       return $this->response($query);
   }
}
