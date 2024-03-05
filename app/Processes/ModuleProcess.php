<?php 

namespace App\Processes;

use App\Models\Module;
use Carbon\Carbon;
use DB;

class ModuleProcess
{

	protected $request, $module;


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
    	$this->module = Module::findOrFail($id);
    	return $this;

    }


    /**
     * Retrive Module.
     *
     * @return 
    */

    public function module()
    {
    	$this->module->refresh();
        $this->module->setRelations([]);
        return $this->module;
    }

    
	/**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {
    	DB::transaction(function (){

            $this->createModule()
                ->createModulePermissions();

            event(new \App\Events\ModuleHasCreated($this->module));
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
            $this->updateModule();
        });
    }

    /**
     * Execute the delete process.
     *
     * @return void
     */
    public function delete()
    {
        DB::transaction(function (){

            $this->deleteRolePermissions()
                ->deleteModulePermissions()
                ->deleteModule();

        });
 
        return $this;
    }

    public function createModule()
    {
        $this->module = Module::create([
            'name'          => lcfirst($this->request->name),
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id()
        ]);

        return $this;
    }

    public function updateModule()
    {
        $this->module->update([
            'name'          => lcfirst($this->request->name),
            'modified_by'   => auth()->id()
        ]);

        return $this;
    }

    public function deleteModule()
    {
        $this->module->delete();

        return $this;
    }

    public function createModulePermissions()
    {
        $module_permissions = $this->formatPermissions();

        $this->module->addPermissions($module_permissions);
        return $this;
    }

    public function deleteModulePermissions()
    {
        $this->module->modulePermissions()->delete();
        return $this;
    }

    public function formatPermissions()
    {
        $module_permissions = [];

        foreach ($this->request->module_permissions as $module_permission) {
            $module_permissions[] = ['permission' => $module_permission]; 
        }

        return $module_permissions;
    }

    public function deleteRolePermissions()
    {
        $this->module->permissions()->delete();
        return $this;
    }


}