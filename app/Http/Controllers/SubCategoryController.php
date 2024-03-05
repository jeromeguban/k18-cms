<?php
namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
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
        $this->authorize('list', SubCategory::class);

        $query = SubCategory::selectedFields()
             ->joinCategory()
             ->withRelations()
             ->searchable()
             ->sortable();


        if(request()->has('category_id')) {

            $array = explode(',', request()->category_id);

            $query->whereIn('sub_categories.category_id', $array);

        }

        if(request()->deleted == "True"){
          $query->onlyTrashed();
        }


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $sub_category)
    {
        $this->authorize('view', $sub_category);

        return SubCategory::selectedFields()
            ->joinCategory()
            ->where('sub_categories.sub_category_id', $sub_category->sub_category_id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy(SubCategory $sub_category)
    {
        $this->authorize('delete', $sub_category);

        $sub_category->delete();

        activity()
            ->performedOn($sub_category)
            ->withProperties($sub_category)
            ->log('Sub Category has been deleted');

        return [
            'success' => 1,
            'data' => $sub_category
        ];
    }
}
