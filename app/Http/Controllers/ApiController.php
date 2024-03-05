<?php

namespace App\Http\Controllers;

use App\Models\Api;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApiController extends Controller
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
        $this->authorize('list', Api::class);

        $query = Api::withRelations()
                ->searchable()
                ->sortable();
        
        return $this->response($query);
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActive(Request $request, Api $api)
    {
        $this->authorize('update', $api);
        $api->update([
            'active'  => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn( $api )
            ->withProperties($api)
            ->log('Api has been updated');

        return [
            'success' => 1,
            'data' => $api
        ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Api $api)
    {
        $this->authorize('delete', $api);

        $api->delete();


        activity()
            ->performedOn( $api )
            ->withProperties($api)
            ->log('Api has been deleted');

        return [ 'success' => 1 ];
    }
}
