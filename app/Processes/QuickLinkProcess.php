<?php 
namespace App\Processes;

use App\Models\QuickLink;
use App\Processes\QuickLinkIconProcess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuickLinkProcess
{
	protected $request, $quickLink;

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
     * Retrive Adds.
     *
     * @return 
    */

	public function find($id)
    {

        $this->quickLink = QuickLink::findOrFail($id);
        
        return $this;

    }

    public function quickLink()
    {
        return $this->quickLink;
    }

    /**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {

    	DB::transaction(function(){

           $this->createQuickLink()
                ->saveIcon();
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
            $this->updateQuickLink();
        });
        
    }

    public function createQuickLink()
    {

    	$last_item = QuickLink::count() ? QuickLink::orderBy('sequence', 'desc')->first() : 0;
    	$this->quickLink = QuickLink::create([
            'name'          => $this->request->name,
    		'label'		    => $this->request->label,
            'slogan'        => $this->request->slogan,
    		'link'		    => "/" . $this->request->link,
    		'sequence'      => $last_item ? $last_item->sequence + 1 : $last_item + 1 ,
            'gradient'      => str_replace(["\"[", "]\""], ["[", "]"],   str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->gradient)))),
    		'created_by'	=> auth()->id(), 
    		'modified_by'	=> auth()->id(),
    	]);

    	return $this;
    }

    private function updateQuickLink()
    {
    	$this->quickLink->update([
            'name'          => $this->request->name,
    		'label'		    => $this->request->label,
            'slogan'        => $this->request->slogan,
    		'link'		    => $this->request->link,
            'gradient'     => str_replace(["\"[", "]\""], ["[", "]"],   str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->gradient)))),
    		'modified_by'	=> auth()->id(),
    	]);

    	return $this;
    }

    private function saveIcon()
    {

    	(new QuickLinkIconProcess($this->quickLink, $this->request->file('icon')))->upload();

    	return $this;
    }

}