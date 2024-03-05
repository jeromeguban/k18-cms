<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Models\Address\Province;
use App\Models\Address\Municipality;
use App\Http\Controllers\Controller;

class ProvinceMunicipalitiesController extends Controller
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
    public function index($province_name)
    {
        $query = Municipality::withRelations()
            ->searchable()
            ->sortable()
            ->whereProvinceName($province_name);

        return $this->response($query)->pluck('citymunDesc');
    }
}
