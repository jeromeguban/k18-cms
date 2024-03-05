<?php

namespace App\Processes;

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class UserProcess
{
    protected $user;
    protected $request;

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

            $this->createUser()
                ->assignStores()
                ->assignRoles();

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

            $this->updateUser()
                ->deleteRoles()
                ->assignStores()
                ->assignRoles();

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

            $this->deleteRoles()
                ->deleteUser();

        });
    }

    public function find($id)
    {
        $this->user = User::findOrFail($id);

        return $this;
    }

    public function refresh()
    {
        $this->user->refresh();
        $this->user->setRelations([]);

        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    public function createUser()
    {
        $this->user = User::Create([
            'first_name'    => $this->request->first_name,
            'last_name'     => $this->request->last_name,
            'email'         => $this->request->email,
            'phone'         => $this->request->phone,
            'password'      => bcrypt($this->request->password),
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id()
        ]);

        return $this;
    }

    public function updateUser()
    {
        $this->user->update([
            'first_name'    => $this->request->first_name,
            'last_name'     => $this->request->last_name,
            'email'         => $this->request->email,
            'phone'         => $this->request->phone,
            'password'      => $this->request->password ? bcrypt($this->request->password) : $this->user->password,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id()
        ]);

        return $this;
    }

    public function deleteUser()
    {
        $this->user->delete();

        return $this;
    }

    public function assignRoles()
    {
        $roles = collect($this->request->roles)->pluck('id')->toArray();

        foreach ($roles as $role) {
            $this->user->refresh()->assignRole($role);
        }

        return $this;
    }

    public function assignStores()
    {

        $this->user->store()->syncStore($this->request->stores);

        return $this;
    }

    public function deleteRoles()
    {
        $this->user->revokeAllRoles();

        return $this;
    }
}
