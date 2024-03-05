<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Models\Address\Barangay;
use App\Http\Controllers\Controller;

class MunicipalityBarangaysController extends Controller
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
    public function index($municipality_name)
    {
        $query = Barangay::withRelations()
            ->searchable()
            ->sortable()
            ->whereMunicipalityName($municipality_name);

        return $this->response($query)->pluck('brgyDesc');
    }
}
