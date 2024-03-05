<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Processes\StoreBannerProcess;


class StoreBannerController extends Controller
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
    public function index(Store $store)
    {
        // $this->authorize('view', $auction);

        $query = Store::selectedFields()
                    ->where('id', $store->id)
                    ->get();

        if(!$query){
            return 'Banners Not Found';
        }

        if($query) {
            return $query;
        }
        // return Store::where('id', $store->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, Store $store)
    {

        $request->validate([
            'file'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        // return $request;
        $process = new StoreBannerProcess($store, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn( $store)
            ->withProperties( $store)
            ->log('Successfully added banner in store');

            return [
                'success'   => 1,
                'data'      => $store->refresh()
            ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreBannerProcess $process ,Store $store, $file)
    {

        $process = new StoreBannerProcess($store); 
        $process->remove();

        activity()
            ->performedOn( $store)
            ->withProperties($store)
            ->log('succesfuly remove banner in store');

        return ['success'   => 1];
    }
}
