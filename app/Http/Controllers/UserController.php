<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Processes\UserProcess;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $this->authorize('list', User::class);
        
        $query = User::withRelations()
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
    public function store(UserRequest $request, UserProcess $process)
    {
        $this->authorize('create', User::class);

        $process->create();

        activity()
            ->performedOn( $process->user() )
            ->withProperties($process->user())
            ->log('User has been created');

        return [
            'success' => 1,
            'data' => $process->user()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return User::where('id', $user->id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, UserProcess $process, User $user)
    {
        $this->authorize('update', $user);

        $process->find($user->id)->update();

        activity()
            ->performedOn( $process->user() )
            ->withProperties($process->user())
            ->log('User has been updated');

        return [
            'success' => 1,
            'data' => $process->user()
        ];    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProcess $process, User $user)
    {
        $this->authorize('delete', $user);

        abort_if($user->id == auth()->id(), 403);

        $process->find($user->id)->delete();

        activity()
            ->performedOn( $process->user() )
            ->withProperties($process->user())
            ->log('User has been deleted');

        return ['success' => 1];
    }
}
