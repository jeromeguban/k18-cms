<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulePermissionController extends Controller
{
    protected $module_permission;

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
        //
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
    public function store(Request $request, Module $module)
    {
        $this->authorize('create', ModulePermission::class);

        $request->validate([
            'permission' => 'required|max:191',
        ]);

        DB::transaction(function () use ($module, $request){

            $this->module_permission = $module->addPermissions([
                'permission' => $request->permission
            ]);

            $module->addSlugInRolePermission([
                'permission' => $request->permission
            ]);
        });

        activity()
            ->performedOn( $this->module_permission )
            ->withProperties($this->module_permission)
            ->log('Module Permission has been created');

       return [
            'success' => 1,
            'data'   => $this->module_permission 
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function show(ModulePermission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function edit(ModulePermission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModulePermission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module, ModulePermission $permission)
    {
        $this->authorize('delete', $permission);

        DB::transaction(function () use ($module, $permission){

            $module->deleteSlugInRolePermission($permission->permission);
            
            $permission->delete();

        });

        activity()
            ->performedOn( $permission)
            ->withProperties($permission)
            ->log('Module Permission has been deleted');

        return ['success' => 1];
    }
}
