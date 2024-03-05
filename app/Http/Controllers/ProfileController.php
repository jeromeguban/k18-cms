<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
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
        return User::where('id', auth()->id())
            ->first();
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
        $request->validate([
            'first_name'    => 'nullable',
            'last_name'     => 'nullable',
            'phone'         => 'nullable',
        ]);

        User::find( Auth::id() )
            ->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'phone'         => $request->phone,
                'modified_by'   => auth()->id(),
            ]);

        activity()
            ->performedOn( $user )
            ->withProperties($user)
            ->log('Profile has been updated');

        return [
            'success' => 1,
            'data' => $user
        ];
    }
}
