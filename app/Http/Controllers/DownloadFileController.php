<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    public function index(Request $request)
    {
        $file = request()->filename;
        return response()->download(public_path("files/{$file}"));

    }
}
