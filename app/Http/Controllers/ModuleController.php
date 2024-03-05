<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Module;
use App\Processes\ModuleProcess;
use Illuminate\Http\Request;

class ModuleController extends Controller
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
        $this->authorize('list', Module::class);

        $query = Module::withRelations()
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
    public function store(ModuleRequest $request, ModuleProcess $process)
    {
        $this->authorize('create', Module::class);

        $process->create();

        activity()
            ->performedOn( $process->module() )
            ->withProperties($process->module())
            ->log('Module has been created');

        return [
            'success' => 1,
            'data' => $process->module()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        $this->authorize('view', $module);

        return Module::where('id', $module->id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleRequest $request, ModuleProcess $process, Module $module)
    {
        $this->authorize('update', $module);

        $process->find($module->id)->update();

        activity()
            ->performedOn( $process->module() )
            ->withProperties($process->module())
            ->log('Module has been updated');

        return [
            'success' => 1,
            'data' => $process->module()
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModuleProcess $process, Module $module)
    {
        $this->authorize('delete', $module);

        $process->find($module->id)->delete();

        activity()
            ->performedOn( $process->module() )
            ->withProperties($process->module())
            ->log('Module has been deleted');

        return ['success' => 1];    
    }
}
