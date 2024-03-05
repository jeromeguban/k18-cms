<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdsRequest;
use App\Models\Ads;
use App\Processes\AdsProcess;
use Illuminate\Http\Request;

class AdsController extends Controller
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
        $this->authorize('list', Ads::class);

         $query = Ads::orderBy('sequence', 'ASC')
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
    public function store(AdsRequest $request, AdsProcess $process)
    {

        $this->authorize('create', Ads::class);

        $process->create();

        activity()
            ->performedOn($process->ads())
            ->withProperties($process->ads())
            ->log('Ads has been created');

        return [
            'success'   => 1,
            'data'      => $process->ads()
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
    public function update(AdsRequest $request, AdsProcess $process, Ads $ad)
    {

        $this->authorize('update', Ads::class);

        $process->find($ad->id)
            ->update();

        activity()
            ->performedOn($process->ads())
            ->withProperties($process->ads())
            ->log('Ads has been updated');

        return [
            'data'      => $process->ads(),
            'success'   => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ads $ad)
    {

        $this->authorize('delete', Ads::class);

        $ad->delete();

        activity()
            ->performedOn( $ad )
            ->withProperties($ad)
            ->log('Ads has been deleted');

        return [            
            'success'   => 1
        ];
    }
}
