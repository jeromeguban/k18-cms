<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Processes\TagMobileBannerProcess;
use Illuminate\Http\Request;

class TagMobileBannerController extends Controller
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
    public function index(Tag $tag)
    {
        return Tag::where('id', $tag->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tag $tag)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,gif,png,jpg,JPG'
        ]);

        $process = new TagMobileBannerProcess($tag, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('Successfuly added banner in tag');

            return [
                'success' => 1,
                'data' => $tag->refresh()
            ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TagMobileBannerProcess $process,Tag $tag, $file)
    {
        $process = new TagMobileBannerProcess($tag);
        $process->remove();

        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('succesfuly remove banner in tag');

        return ['success' => 1];
    }
}
