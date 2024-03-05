<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Processes\Api\PostingProcess;
use App\Rules\ValidateApiSignatureKey;

class PostingController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PostingProcess $process)
    {   
        // $request->validate([
        //     'signature' => [
        //         'required',
        //         new ValidateApiSignatureKey
        //     ],
        // ]);

        $process->create();

        return [
            'success'   => 1,
            'data'      => $process->posting()
        ];
    }

}
