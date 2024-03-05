<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductCatalogExport;

class ProductCatalogExcelController extends Controller
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

    public function index()
    {
        return \Excel::download(new ProductCatalogExport,
            'Product Catalog - '.now()->toDateTimeString().'.xlsx');
    }
}
