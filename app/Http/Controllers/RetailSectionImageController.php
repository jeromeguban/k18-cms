<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailSection;
use App\Processes\RetailSectionImageProcess;

class RetailSectionImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RetailSection $retail_section)
    {
        $request->validate([
            'file'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        $process = new RetailSectionImageProcess($retail_section, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn($retail_section)
            ->withProperties($retail_section)
            ->log('Successfully Uploaded');

        return [
            'success'   => 1,
            'data'      =>  $process->upload()
        ];
    }
}
