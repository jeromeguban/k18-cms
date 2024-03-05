<?php

namespace App\Processes;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleProcess
{
    protected $request;
    protected $role;

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
            
            $this->createRole();
            
            event(new \App\Events\RoleHasCreated($this->role));

        });

        return $this;
    }

    /**
     * Execute the update job.
     *
     * @return void
     */
    public function update()
    {
        DB::transaction(function (){
            
            $this->updateRole();

        });
 
        return $this;
    }

    /**
     * Execute the delete job.
     *
     * @return void
     */
     public function delete()
     {
        DB::transaction(function (){
             
             $this->deleteRole();
 
         });
  
         return $this;
     }

    public function find($id)
    {
        $this->role = Role::findOrFail($id);

        return $this;
    }

    public function createRole()
    {
        $this->role = Role::create([
            'name'          => $this->request->name,
            'slug'          => strtolower($this->request->name),
            'description'   => $this->request->description
        ]);

        return $this;
    }

    public function updateRole()
    {
        $this->role->update([
            'name'          => $this->request->name,
            'slug'          => strtolower($this->request->name),
            'description'   => $this->request->description
        ]);

        return $this;
    }

    public function deleteRole()
    {
        $this->role->delete();

        return $this;
    }

    public function role()
    {
        $this->role->refresh();
        $this->role->setRelations([]);
        return $this->role;
    }
}
