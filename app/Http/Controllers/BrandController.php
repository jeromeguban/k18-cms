<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandBannerRequest;
use App\Http\Requests\BrandLogoRequest;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Processes\BrandBannerProcess;
use App\Processes\BrandLogoProcess;
use App\Processes\BrandProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
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
    public function index(Request $request)
    {

        $this->authorize('list', Brand::class);

        $query = Brand::withRelations()
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
    public function store(BrandRequest $request, BrandProcess $process)
    {

        $this->authorize('create', Brand::class);

        $process->create();

        activity()
            ->performedOn($process->brand())
            ->withProperties($process->brand())
            ->log('Brand has been created');

        return [
            'data' => $process->brand(),
            'success' => 1,
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {

        $this->authorize('view', $brand);

        return Brand::withRelations()
            ->where('brand_id', $brand->brand_id)
            ->first();

        //     $brand = Brand::withRelations()
        //     ->where('brand_id',1)
        //     ->get()
        //      ->toArray();

        //  $encrypted = encrypt($brand);

        //  $decrypt = decrypt($encrypted);

        //  return  $decrypt[0]['brand_name'];

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
    public function update(BrandRequest $request, BrandProcess $process, Brand $brand)
    {

        $this->authorize('update', $brand);

        $process->find($brand->brand_id)
            ->update();

        activity()
            ->performedOn($process->brand())
            ->withProperties($process->brand())
            ->log('Brand has been updated');

        return [
            'data' => $process->brand(),
            'success' => 1,
        ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setLogo(BrandLogoRequest $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        DB::transaction(function () use ($brand, $request) {

            if ($request->file('logo')) {
                (new BrandLogoProcess($brand, $request->file('logo')))->upload();
            }

            activity()
                ->performedOn($brand)
                ->withProperties($brand)
                ->log('Brand Logo has been updated');

            return [
                'success' => 1,
                'data' => $brand,
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
    public function setBanner(BrandBannerRequest $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        DB::transaction(function () use ($brand, $request) {

            if ($request->file('banner')) {
                (new BrandBannerProcess($brand, $request->file('banner')))->upload();
            }

            activity()
                ->performedOn($brand)
                ->withProperties($brand)
                ->log('Brand Banner has been updated');

            return [
                'success' => 1,
                'data' => $brand,
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
    public function setActive(Request $request, Brand $brand)
    {
        $this->authorize('update', $brand);
        $brand->update([
            'active' => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($brand)
            ->withProperties($brand)
            ->log('Brand has been updated');

        return [
            'success' => 1,
            'data' => $brand,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setFeatured(Request $request, Brand $brand)
    {
        $this->authorize('update', $brand);
        $brand->update([
            'featured' => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($brand)
            ->withProperties($brand)
            ->log('Brand has been updated');

        return [
            'success' => 1,
            'data' => $brand,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {

        $this->authorize('delete', $brand);

        DB::transaction(function () use ($brand) {
            $brand->update([
                'deleted_by' => auth()->id(),
            ]);

            $brand->delete();
            activity()
                ->performedOn($brand)
                ->withProperties($brand)
                ->log('Brand has been deleted');
        });

        return [
            'success' => 1,
        ];

    }
}
