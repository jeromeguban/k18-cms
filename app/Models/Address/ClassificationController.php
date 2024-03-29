<?php

namespace App\Http\Controllers\Address;

use App\Models\Address\Classification;
use App\Http\Controllers\Controller;

class ClassificationController extends Controller
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
        $query = Classification::withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query);
    }
}
