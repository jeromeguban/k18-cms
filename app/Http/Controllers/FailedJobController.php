<?php

namespace App\Http\Controllers;

use App\Models\FailedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FailedJobController extends Controller
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
        $this->authorize('list', FailedJob::class);

        $query = FailedJob::withRelations()
                    ->searchable()
                    ->sortable();

        return $this->response($query);
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FailedJob $failed_job)
    {
        $this->authorize('view', $failed_job);

        return FailedJob::where('id', $failed_job->id)
                    ->withRelations()
                    ->first();
    }
}
