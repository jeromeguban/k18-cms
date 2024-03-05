<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PostingReportExport;

class PostingReportController extends Controller
{
    public function index()
    {
        return \Excel::download(new PostingReportExport, 
            'Report - '.now()->toDateTimeString().'.xlsx');
    }
}
