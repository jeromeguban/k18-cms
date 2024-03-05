<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class CredentialController extends Controller
{

    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    public function update( Request $request) {

        $this->user = User::where('email', $request->username)
            ->first();

        $this->user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response(['data' => "Successfully Updated"]);

    }

}
