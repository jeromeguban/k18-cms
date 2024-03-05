<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Processes\RoleProcess;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', Role::class);

        $query = Role::withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request, RoleProcess $process)
    {
        $this->authorize('create', Role::class);

        $process->create();

        activity()
            ->performedOn( $process->role() )
            ->withProperties ($process->role())
            ->log('Role has been created');
        
        return [
            'success' => 1,
            'data'  => $process->role()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($role)
    {
        $this->authorize('view', Role::findOrFail($role));

        return Role::where('id', $role)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, RoleProcess $process, $role)
    {
        $this->authorize('update', Role::findOrFail($role));

        $process->find($role)->update();

        activity()
            ->performedOn( $process->role() )
            ->withProperties ($process->role())
            ->log('Role has been updated');

        return [
            'success' => 1,
            'data'  => $process->role()
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleProcess $process, $role)
    {
        $this->authorize('delete', Role::findOrFail($role));

        $process->find($role)->delete();

        activity()
            ->performedOn( $process->role() )
            ->withProperties ($process->role())
            ->log('Role has been deleted');
        
        return ['success' => 1];
    }
}
