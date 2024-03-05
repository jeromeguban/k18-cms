<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Models\Address\Province;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
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
        $query = Province::withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query)->pluck('provDesc');
    }
}
