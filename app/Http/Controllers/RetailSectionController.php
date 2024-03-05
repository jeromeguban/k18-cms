<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailSection;
use Illuminate\Support\Facades\DB;
use App\Processes\RetailSectionProcess;
use App\Http\Requests\RetailSectionRequest;


class RetailSectionController extends Controller
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

        $this->authorize('list', RetailSection::class);

        $query = RetailSection::selectedFields()
            ->searchable()
            ->orderBy('id', 'ASC');

        // dd(str_replace(["\"[{", "}]\""], ["[{", "}]"], str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($query->properties))))));

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RetailSectionRequest $request, RetailSectionProcess $process)
    {
        $this->authorize('create', RetailSection::class);

        $process->create();

        activity()
            ->performedOn($process->retail_section())
            ->withProperties($process->retail_section())
            ->log('Auction Section has been created');

        return [
            'success' => 1,
            'data' => $process->retail_section()
        ];
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RetailSection $retail_section)
    {

        $this->authorize('view', $retail_section);

        return RetailSection::selectedFields()
            ->where('id', $retail_section->id)
            ->first();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RetailSectionRequest $request,  RetailSectionProcess $process, RetailSection $retail_section)
    {
        $this->authorize('update', $retail_section);

        $process->find($retail_section->id)
            ->update();

        activity()
            ->performedOn($process->retail_section())
            ->withProperties($process->retail_section())
            ->log('Auction Section has been updated');

        return [
            'data'      => $process->retail_section(),
            'success'   => 1
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RetailSection $retail_section)
    {
        $this->authorize('delete', $retail_section);

        $retail_section->delete();

        activity()
            ->performedOn($retail_section)
            ->withProperties($retail_section)
            ->log('Auction Section has been deleted');

        return ['success' => 1];
    }
}
