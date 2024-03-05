<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Queries\ProductCatalogResult;

class ProductCatalogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $query = ProductCatalogResult::query();

        $response = $this->response($query);

        return $response;
    }
}
