<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Section;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\SectionRequest;
use App\Processes\SectionProcess;

class SectionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $this->authorize('list', Section::class);

        $query = Section::withRelations()
                        ->whereQueryScopes()
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
    public function store(SectionRequest $request, SectionProcess $process)
    {
        $this->authorize('create', Section::class);

        $process->create();

        activity()
            ->performedOn( $process->section() )
            // ->withProperties($process()->section())
            ->log('Section has been created');

        return [
            'success' => 1,
            'data' => $process->section()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        $this->authorize('view', $section);

        return Section::where('id', $section->id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

     public function update(SectionRequest $request, Section $section, SectionProcess $process)
    {
        $this->authorize('update', $section);

        $process->find($section->id)
            ->update();

        activity()
            ->performedOn( $section )
            // ->withProperties($section)
            ->log('Section has been updated');

        return [
            'success' => 1,
            'data' => $section
        ];
    }
    
    public function changeStatus(Request $request, Section $section)
    {
        $section->update([
            'active'  =>  $request->active == 0 ? 1 : 0,
        ]);

        activity()
        ->withProperties($section)
        ->log('Section Status has been updated');

        return [
            'success'   => 1
        ];
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $this->authorize('delete', $section);

        $section->delete();

        activity()
            ->performedOn( $section )
            ->withProperties($section)
            ->log('Section has been deleted');

        return [ 'success' => 1 ];
    }

    public function getAuction()
    {
        
    }

    public function updateSequence(Request $request)
    {
        foreach (request()->sequence as $index => $id) {
            $section = Section::find($id);

            if ($section) {
                $section->update([
                    'sequence'      => (int)$index + 1
                ]);
            }

            activity()
                ->performedOn($section->refresh())
                ->withProperties($section->refresh())
                ->log('Section Sequence has been updated');
        }

        return [
            'success'   => 1
        ];
    }

    

}
