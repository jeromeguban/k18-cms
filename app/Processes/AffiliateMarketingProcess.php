<?php

namespace App\Processes;

use App\Models\AffiliateMarketing;
use Illuminate\Support\Facades\DB;

class AffiliateMarketingProcess
{  
    protected $request, $affiliate_marketing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = ($request) ? (object) $request : request();
    }

    /**
     * Execute the create job.
     *
     * @return void
    */
    public function create()
    {
        DB::transaction(function (){

            $this->createAffiliate();

        });
    }

    /**
     * Execute the update job.
     *
     * @return void
    */
    public function update()
    {
        DB::transaction(function (){

            $this->updateAffiliate();

        });
    }

    /**
     * Execute the delete job.
     *
     * @return void
    */
    public function delete()
    {
        DB::transaction(function () {

            $this->deleteAffiliate();

        });
    }

    public function find($id)
    {
        $this->affiliate_marketing = AffiliateMarketing::findOrFail($id);

        return $this;
    }

    public function affiliate_marketing()
    {
        return $this->affiliate_marketing;
    }

    public function createAffiliate()
    {

        $fname = substr($this->request->first_name, 0, 1);
        $lname = substr($this->request->last_name, 0, 1);
        $mname = substr($this->request->middle_name, 0, 1);
        $twoDigitRandomNumber = rand(1,99);


        $this->affiliate_marketing = AffiliateMarketing::Create([
            'code'          => strtoupper($fname.$mname.$lname.$twoDigitRandomNumber),
            'commission'    => $this->request->commission,
            'first_name'    => $this->request->first_name,
            'last_name'     => $this->request->last_name,
            'middle_name'   => $this->request->middle_name,
            'email'         => $this->request->email,
            'phone_no'      => $this->request->phone_no,
            'created_by'    => auth()->id(),
            'active'        => $this->request->active ? 1 : 0,
        ]);

        // $this->affiliate_marketing->update([
        //     'code'          => strtoupper($fname.$mname.$lname.$this->affiliate_marketing->id),
        // ]);

        return $this;
    }

    public function updateAffiliate()
    {
     
        $this->affiliate_marketing->update([
            'commission'    => $this->request->commission,
            'first_name'    => $this->request->first_name,
            'last_name'     => $this->request->last_name,
            'middle_name'   => $this->request->middle_name,
            'email'         => $this->request->email,
            'phone_no'      => $this->request->phone_no,
            'modified_by'   => auth()->id(),
            'updated_at'    => now()->toDateTimeString(),
            'active'        => $this->request->active ? 1 : 0,
        ]);

        return $this;
    }

    public function deleteAffiliate()
    {
        $this->affiliate_marketing->delete();

        return $this;
    }
}