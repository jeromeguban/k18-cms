<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleProductCategory;

class GoogleProductCategoryController extends Controller
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
        $query = GoogleProductCategory::searchable()
                                ->sortable();

        return $this->response($query);
    }
}
