<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserForPendingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->Update([
            'status'  => "Pending",
            'modified_by'   => auth()->id(),
        ]);

        activity()
            ->performedOn( $user )
            ->withProperties($user)
            ->log('User has been Pending');

        return [
            'success'   => 1,
            'data'  => $user
        ];
    }


}