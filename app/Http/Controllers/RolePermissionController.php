<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
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
    public function index($role)
    {
        $this->authorize('list', Permission::class);

        $query = Permission::withRelations()
            ->searchable()
            ->sortable()
            ->orderBy('permissions.name', 'ASC')
            ->whereRoleId($role);

        return $this->response($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($role, Permission $permission)
    {
        $this->authorize('view', $permission);

        return Permission::where('id', $permission->id)
            ->whereRoleId($role)
            ->withRelations()
            ->first();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role, Permission $permission)
    {

        $this->authorize('update', $permission);

        $request->validate([
            'permissions'               => 'required|array',
            'permissions.*.status'      => 'required|boolean',
            'permissions.*.permission'  => [
                'required',
                Rule::exists('module_permissions')->where(function ($query) use ($permission) {
                    $query->where('module_id', $permission->module->id);
                }),
            ],
        ]);

        // This will generate the correct format of slug.
        $slug = collect($request->permissions)->mapWithKeys(function ($permission, $key) {
            return [ $permission['permission'] => $permission['status'] ];
        })->toArray();

        $permission->update([
            'slug' => $slug
        ]);

        return [
            'success' => 1,
            'data' => $permission
        ];
    }

}
