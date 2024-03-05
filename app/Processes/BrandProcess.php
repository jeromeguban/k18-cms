<?php 

namespace App\Processes;

use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class BrandProcess
{

	protected $request, $brand;


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

    	$this->brand = Brand::findOrFail($id);
        
    	return $this;

    }


    /**
     * Retrive Brand.
     *
     * @return 
    */

    public function brand()
    {
        
    	return $this->brand;
        
    }

    
	/**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {
        
    	DB::transaction(function(){
    		$this->createBrand();
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
            $this->updateBrand();
        });
        
    }

    /**
     * Create Brand.
     *
     * @return void
    */

    private function createBrand()
    {
       
    	$this->brand = Brand::create([
    		'brand_name'    => $this->request->brand_name,
            'brand_code'    => $this->request->brand_code,
            'caption'       => $this->request->caption,
            'active'        => $this->request->active ? 1 : 0,
            'featured'      => $this->request->featured ? 1 : 0,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id(),
    	]);

    	return $this;
        
    }

    /**
     * Update Brand.
     *
     * @return void
    */

    private function updateBrand()
    {

    	$this->brand->update([
    		'brand_name'    => $this->request->brand_name,
            'brand_code'    => $this->request->brand_code,
            'caption'       => $this->request->caption,
            'active'        => $this->request->active ? 1 : 0,
            'featured'      => $this->request->featured ? 1 : 0,
            'modified_by'   => auth()->id(),
    	]);

    	return $this;
        
    }

}