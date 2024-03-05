<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Processes\SectionBannerProcess;

class SectionBannerController extends Controller
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
    public function index(Section $section)
    {
        // $this->authorize('view', $auction);

        $query = Section::selectedFields()
                    ->where('id', $section->id)
                    ->get();

        if(!$query){
            return 'Banners Not Found';
        }

        if($query) {
            return $query;
        }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, Section $section)
    {

        $request->validate([
            'file'  => 'required|mimes:jpeg,png,jpg,JPG'
        ]);

        // return $request;
        $process = new SectionBannerProcess($section, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn( $section)
            ->withProperties( $section)
            ->log('Successfully added banner in section');

            return [
                'success'   => 1,
                'data'      => $section->refresh()
            ];

    }
}
