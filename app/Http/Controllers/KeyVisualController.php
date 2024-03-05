<?php

namespace App\Http\Controllers;

use App\Http\Requests\KeyVisualRequest;
use App\Models\KeyVisual;
use App\Processes\KeyVisualProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeyVisualController extends Controller
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
    public function index(Request $request)
    {

        $this->authorize('list', KeyVisual::class);

        $query = KeyVisual::orderBy('sequence', 'ASC')
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
    public function store(KeyVisualRequest $request, KeyVisualProcess $process)
    {
        $this->authorize('create', KeyVisual::class);

        $process->create();

         activity()
            ->performedOn($process->keyVisual())
            ->withProperties($process->keyVisual())
            ->log('Key Visual has been created');

        return [
            'success'   => 1,
            'data'      => $process->keyVisual()
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(KeyVisual $key_visual)
    {



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KeyVisualRequest $request, KeyVisualProcess $process, KeyVisual $key_visual)
    {
        $this->authorize('update', $key_visual);

        $process->find($key_visual->id)
            ->update();

        activity()
            ->performedOn( $process->keyVisual() )
            ->withProperties($process->keyVisual())
            ->log('Key Visual has been updated');

        return [
            'data'      => $process->keyVisual(),
            'success'   => 1
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeyVisual $keyVisual)
    {
        $this->authorize('delete', KeyVisual::class);

        $keyVisual->delete();
        activity()
            ->performedOn( $keyVisual )
             ->withProperties($keyVisual)
            ->log('Key Visual has been deleted');

        return [
            'success'   => 1
        ];
    }
}
