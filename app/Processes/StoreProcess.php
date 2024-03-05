<?php

namespace App\Processes;

use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\{Arr, Str};

class StoreProcess
{
    protected $request, $store;

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
        DB::transaction(function () {

            $this->createStore()
                ->assignStoreCourier();
        });
    }

    /**
     * Execute the update job.
     *
     * @return void
     */
    public function update()
    {
        DB::transaction(function () {

            $this->updateStore()
                ->deleteStoreCouriers()
                ->assignStoreCourier();
        });
    }


    public function find($id)
    {
        $this->store = Store::findOrFail($id);

        return $this;
    }

    public function store()
    {
        return $this->store;
    }

    public function createStore()
    {   
        $this->store = Store::create([
            'code'              => $this->request->code,
            'company_id'        => $this->request->company_id,
            'store_company_name' => $this->request->store_company_name,
            'store_name'        => $this->request->store_name,
            'slug'              => Str::kebab(strtolower($this->request->store_name)),
            'address_line'      => $this->request->address_line,
            'extended_address'  => $this->request->extended_address,
            'contact_number'    => $this->request->contact_number,
            'email'             => $this->request->email,
            'description'       => $this->request->description,
            'store_type'        => $this->request->store_type,
            'store_company_code'=> $this->request->store_company_code,
            'created_by'        => auth()->id(),
            'modified_by'       => auth()->id(),
            'classification_id' => $this->request->classification_id,
            'delivery'          => $this->request->delivery ? 1 : 0
        ]);

        return $this;
    }

    public function updateStore()
    {
        $this->store->update([
            'code'              => $this->request->code,
            'company_id'        => $this->request->company_id,
            'store_company_name'=> $this->request->store_company_name,
            'store_name'        => $this->request->store_name,
            'slug'              => Str::kebab(strtolower($this->request->store_name)),
            'address_line'      => $this->request->address_line,
            'extended_address'  => $this->request->extended_address,
            'contact_number'    => $this->request->contact_number,
            'description'       => $this->request->description,
            'email'             => $this->request->email,
            'store_type'        => $this->request->store_type,
            'store_company_code'=> $this->request->store_company_code,
            'modified_by'       => auth()->id(),
            'classification_id' => request()->classification_id,
            'delivery'          => $this->request->delivery ? 1 : 0
        ]);

        return $this;
    }

    public function assignStoreCourier()
    {

        $this->store->courier()->syncStoreCouriers($this->request->couriers);
        return $this;
    }

    public function deleteStoreCouriers()
    {
        $this->store->storeCouriers()->delete();

        return $this;
    }
}
