<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InquireEmail;
use Illuminate\Support\Facades\DB;

class InquireEmailController extends Controller
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
        $this->authorize('list', InquireEmail::class);

        $query = InquireEmail::withRelations()
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
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'emails'    => 'required',
            'emails.*'  => 'required|email',
            'category'  => 'required',
        ], [], [
            'name'      => 'Name',
            'emails'    => 'Email(s)',
            'emails.*'  => 'Email',
            'category'  => 'Mail For',
        ]);

        $active_inquire_email = InquireEmail::where('active', 1)
                                        ->where('category', $request->category)
                                        ->first();

        if($active_inquire_email){
            $active_inquire_email->update([
                'active'    => 0
            ]);
        }

        $inquire_email = InquireEmail::create([
            'name'      => $request->name,
            'active'    => $request->active ? 1 : 0 ,
            'emails'    => json_encode($request->emails),
            'category'  => $request->category,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id()
        ]);

        activity()
            ->performedOn( $inquire_email )
            ->withProperties($inquire_email)
            ->log('Contact Us Mailer Settings has been created');

        return [
            'success' => 1,
            'data' => $inquire_email
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
    public function edit(InquireEmail $inquire_email)
    {
        $this->authorize('view', $inquire_email);

        return InquireEmail::where('id', $store->id)
                    ->withRelations()
                    ->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InquireEmail $inquire_email)
    {
        $this->authorize('update', $inquire_email);

        $request->validate([
            'name'      => 'required',
            'emails'    => 'required',
            'emails.*'  => 'required|email',
            'category'  => ''
        ], [], [
            'name'      => 'Name',
            'emails'    => 'Email(s)',
            'emails.*'  => 'Email'
        ]);

        $inquire_email->update([
            'name'              => $request->name,
            'category'          => $request->category,
            'emails'            => json_encode($request->emails),
            'modified_by'       => auth()->id(),
        ]);

        activity()
            ->performedOn( $inquire_email )
            ->withProperties($inquire_email)
            ->log('Contact Us Mailer Settings has been updated');

        return [
            'success' => 1,
            'data' => $inquire_email
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InquireEmail $inquire_email)
    {
        $this->authorize('delete', $inquire_email);

        DB::transaction(function() use ($inquire_email) {

            $inquire_email->update([
                'deleted_by'    => auth()->id()
            ]);

            $inquire_email->delete();
            
            activity()
                ->performedOn( $inquire_email )
                ->withProperties($inquire_email)
                ->log('Contact Us Mailer Settings has been deleted');
        });

        return [ 'success'   => 1 ];
    }

    public function setActive(Request $request,InquireEmail $inquire_email)
    {
        $this->authorize('update', $inquire_email);

        $active_inquire_email = InquireEmail::where('active', 1)
                                            ->where('category', $request->category)
                                            ->first();

        if($active_inquire_email){
            $active_inquire_email->update([
                'active'    => 0
            ]);
        }

        $inquire_email->update([
            'active'              => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn( $inquire_email )
            ->withProperties($inquire_email)
            ->log('Contact Us Mailer Settings has been set to active');

        return [
            'success' => 1,
            'data' => $inquire_email
        ];
    }
}
