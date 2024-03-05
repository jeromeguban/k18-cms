<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Processes\TagLogoProcess;
use Illuminate\Support\Facades\DB;
use App\Processes\TagBannerProcess;
use App\Http\Requests\TagLogoRequest;
use App\Http\Requests\TagBannerRequest;
use App\Processes\TagMobileBannerProcess;
use App\Http\Requests\TagMobileBannerRequest;

class TagController extends Controller
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
        $this->authorize('list', Tag::class);

        $query = Tag::selectedFields()
            ->whereQueryScopes()
            ->withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);

        $request->validate([
            'name'               => 'required|unique:tags',
        ]);

        $tag = Tag::create([
            'name'          => $request->name,
            'slug'          => Str::kebab(strtolower($request->name)),
            'featured'      => $request->featured ? 1 : 0,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id()
        ]);


        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('Tag has been created');

        return [
            'success' => 1,
            'data' => $tag
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        return Tag::selectRaw("
                        tags.*,
                        (CASE
                            WHEN tags.featured = 0 THEN 'No'
                            WHEN tags.featured = 1 THEN 'YES'
                        END) AS 'featured'
                    ")
            ->where('id', $tag->id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $request->validate([
            'name'  => [
                'required',
                Rule::unique('tags')->ignore($tag->id),
            ],
        ]);

        $tag->update([
            'name'              => request()->name,
            'slug'              => Str::kebab(strtolower(request()->name)),
            'featured'          => request()->featured ? 1 : 0,
            'modified_by'       => auth()->id(),
        ]);

        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('Tag has been updated');

        return [
            'success' => 1,
            'data' => $tag
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setLogo(TagLogoRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        DB::transaction(function () use ($tag, $request) {

            if ($request->file('logo')) (new TagLogoProcess($tag, $request->file('logo')))->upload();

            activity()
                ->performedOn($tag)
                ->withProperties($tag)
                ->log('Tag Logo has been updated');

            return [
                'success' => 1,
                'data' => $tag
            ];
        });
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setBanner(TagBannerRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        DB::transaction(function () use ($tag, $request) {

            if ($request->file('banner')) (new TagBannerProcess($tag, $request->file('banner')))->upload();

            activity()
                ->performedOn($tag)
                ->withProperties($tag)
                ->log('Tag Banner has been updated');

            return [
                'success' => 1,
                'data' => $tag
            ];
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setMobileBanner(TagMobileBannerRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        DB::transaction(function () use ($tag, $request) {

            if ($request->file('mobile_banner')) (new TagMobileBannerProcess($tag, $request->file('mobile_banner')))->upload();

            activity()
                ->performedOn($tag)
                ->withProperties($tag)
                ->log('Tag Banner has been updated');

            return [
                'success' => 1,
                'data' => $tag
            ];
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActive(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $tag->update([
            'active'              => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('Tag has been updated');

        return [
            'success' => 1,
            'data' => $tag
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setFeatured(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $tag->update([
            'featured'              => $request->featured == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('Tag has been updated');

        return [
            'success' => 1,
            'data' => $tag
        ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();


        activity()
            ->performedOn($tag)
            ->withProperties($tag)
            ->log('Tag has been deleted');

        return ['success' => 1];
    }
}
