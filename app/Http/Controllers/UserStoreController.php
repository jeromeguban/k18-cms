<?php

namespace App\Http\Controllers;

use App\Models\UserStore;
use App\Models\User;
use Illuminate\Http\Request;

class UserStoreController extends Controller
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
    public function index(User $user)
    {
        $query = UserStore::selectedFields()
                    ->joinStore()
                    ->sortable();

        return $this->response($query);
    }

}
