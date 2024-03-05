<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfilePasswordController extends Controller
{
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ]);

        // return $request;

        $user->update([
            'password'      => bcrypt($request->password),
            'modified_by'   => auth()->id(),
        ]);

        activity()
            ->performedOn( $user )
            ->withProperties($user)
            ->log('Password of User has been updated');

        return [
            'success' => 1,
            'data' => $user
        ];
    }
}
