<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuickLinkRequest;
use App\Models\QuickLink;
use App\Processes\QuickLinkProcess;
use Illuminate\Http\Request;

class QuickLinkController extends Controller
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
        $this->authorize('list', Quicklink::class);

        $query = QuickLink::orderBy('sequence', 'ASC')
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
    public function store(QuickLinkRequest $request, QuickLinkProcess $process)
    {
        $this->authorize('create', Quicklink::class);

        $process->create();

        activity()
            ->performedOn($process->quickLink())
            ->log('Quick Link has been created');

        return [
            'success'   => 1,
            'data'      => $process->quickLink()
        ];
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(QuickLinkRequest $request, QuickLinkProcess $process, QuickLink $quicklink)
    {

        $this->authorize('update', Quicklink::class);

        $process->find($quicklink->id)
            ->update();

        activity()
            ->performedOn( $process->quickLink() )
            ->withProperties($process->quickLink())
            ->log('Quick Link has been updated');

        return [
            'data'      => $process->quickLink(),
            'success'   => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuickLink $quicklink)
    {

        $this->authorize('delete', Quicklink::class);

        $quicklink->delete();

        activity()
            ->performedOn( $quicklink )
            ->withProperties($quicklink)
            ->log('Quick Link has been deleted');

        return [            
            'success'   => 1
        ];
    }
}
