<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    public function login( Request $request) {

        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $this->user = User::where('email', $request->email)
            ->first();

        if($this->user->status == 'Pending'){

            return response(['message' => 'Account must need to Approved by Administrator' ]);
        }
        

        if (!Auth::attempt($login)){

            return response(['message' => 'Ivalid Login Credentials' ]);
            
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['access_token' =>  $accessToken ]);

    }

}
