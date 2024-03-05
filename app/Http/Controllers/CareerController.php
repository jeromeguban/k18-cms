<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use App\Http\Requests\CareerRequest;
use App\Processes\CareerBannerProcess;
use App\Processes\CareerProcess;
use Illuminate\Support\Facades\DB;

class CareerController extends Controller
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

    public function index()
    {
        $this->authorize('list', Career::class);

        $query = Career::selectedFields()
                        ->sortable()
                        ->searchable();

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CareerRequest $request,CareerProcess $process)
    {
       $this->authorize('create', Career::class);

        $process->create();

        activity()
            ->performedOn( $process->career() )
            ->withProperties($process->career())
            ->log('Career has been created');

        return [
            'success'   => 1,
            'data'      => $process->career()
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(CareerRequest $request, CareerProcess $process, Career $career)
    {
        $this->authorize('update', $career);

        $process->find($career->career_id)
            ->update();

        activity()
            ->performedOn($process->career())
            ->withProperties($process->career())
            ->log('Career has been updated');

        return [
            'success' => 1,
            'data' => $process->career(),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function show(Career $career)
    {
        $this->authorize('view', $career);

        return Career::where('career_id', $career->career_id)
            ->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Career $career)
    {

        $this->authorize('delete', $career);

        DB::transaction(function () use ($career) {
            $career->update([
                'deleted_by' => auth()->id(),
            ]);

            $career->delete();

            activity()
                ->performedOn($career)
                ->withProperties($career)
                ->log('Career has been deleted');
        });

        return [
            'success' => 1,
        ];

    }

    public function banner(Request $request, Career $career) {


        $request->validate([
            'banner'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        (new CareerBannerProcess($career, $request->file('banner')))->upload();

        activity()
            ->performedOn( $career)
            ->withProperties( $career)
            ->log('Successfully update banner in career');

            return [
                'success'   => 1,
                'data'      => $career->refresh()
            ];
    }
}
