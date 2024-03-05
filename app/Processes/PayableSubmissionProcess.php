<?php 

namespace App\Processes;

use Carbon\Carbon;
use App\Models\Cost;
use App\Models\OrderPosting;
use App\Models\Company;
use App\Models\Payable;
use App\Models\CostType;
use Illuminate\Support\Facades\DB;

class PayableSubmissionProcess
{

	protected $request, $payable, $items, $cost_type;


	 /**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{
		$this->request = $request ? (object) $request : request();

	}


	/**
     * Execute find process.
     *
     * @return void
    */

    public function find($id)
    {
        $this->payable = Payable::findOrFail($id);

        $this->cost_type = CostType::where('type', 'Additional Cost')->first();

        abort_unless($this->cost_type, 403, 'Cost Type "Additional Cost" is required');

    	return $this;
    }


    /**
     * Retrive Payable.
     *
     * @return 
    */

    public function payable()
    {
    	return $this->payable;
    }

    
	/**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {
    	DB::transaction(function(){

    		$this->createPayable()
                ->updateCompanyPostings()
                ->updateCost();
                
    	});
    }


    /**
     * Execute update process.
     *
     * @return void
    */

    public function update()
    {
    	DB::transaction(function(){
            $this->updatePayable();
        });
    }


    
    private function createPayable()
    {
        $company = Company::where('id', $this->request->company_id)
                        ->firstOrFail();

        $this->payable = Payable::create([
            'company_id'    => $company->id,
            'store_company_code' => $company->company_code,
            'commission'    => $company->default_commission,
            'remarks'       => $this->request->remarks,
            'generate_date' => Carbon::parse(now())->toDateString(),
            'status'        => "Open",
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id(),
            'date_from'     => $this->request->from,
            'date_to'       => $this->request->to,
        ]);

        return $this;
    }

    private function updatePayable()
    {
        $this->payable->update([
            'status'    => 'Submitted'
        ]); 

        return $this;
    }

    private function updateCompanyPostings()
    {
        $query = OrderPosting::selectedFields()
                    ->join('orders', 'orders.id', '=', 'order_postings.order_id')
                    ->join('payments', 'payments.id', '=', 'orders.payment_id')
                    ->join('stores', 'stores.id', '=', 'orders.store_id')
                    ->where('stores.company_id', $this->payable->company_id)
                    ->whereNotNull('orders.payment_id')
                    ->where('orders.payment_status', 'Paid')
                    ->whereNull('order_postings.payable_id')
                    ->whereNull('orders.cancelled_date')
                    ->whereNull('orders.deleted_at')
                    ->whereNull('payments.deleted_at')
                    ->when($this->request->excluded_items && count($this->request->excluded_items), function($query) {
                        $query->whereNotIn('order_postings.id', $this->request->excluded_items);
                    });

        if($this->request->from){

            $from = Carbon::parse($this->request->from.' 00:00:00')->toDateTimeString();
            $to = $this->request->to ? Carbon::parse($this->request->to.' 23:59:59')->toDateTimeString() : now()->toDateTimeString();

            $query->whereBetween('payments.created_at' , [$from, $to]);

        }


        $query->update([
            'payable_id' => $this->payable->id
        ]);

        return $this;
    }

    private function updateCost()
    {   

        if(!count($this->request->costs))
            return $this;

        $costs = collect($this->request->costs)
                    ->pluck('id')
                    ->toArray();

        Cost::where('company_id', $this->payable->company_id)
            ->whereNull('payable_id')
            ->whereIn('id', $costs)
            ->update([
                'payable_id'    => $this->payable->id,
                'modified_by'   => auth()->id()
            ]);

        return $this;
    }

}