<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserResetLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function show($token)
    {
        return view('auth.passwords.reset')
            ->with('token', $token);
    }

    public function update(Request $request)
    {
        //Validate input
        $this->validate($request, [
            'token'     => 'required|exists:password_resets,token',
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|confirmed|min:6',
        ]);

        // Validate token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        // Validate email
        $user = User::where('email', $tokenData->email)->first();

        // Update password and remove token
        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_resets')
            ->where('email', $user->email)
            ->delete();

        // Log user in
        auth()->login($user);

    }
}
