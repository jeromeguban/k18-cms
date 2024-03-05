<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Models\Address\Zipcode;
use App\Http\Controllers\Controller;

class ZipcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Zipcode::withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query)->pluck('zip_code');
    }
}
