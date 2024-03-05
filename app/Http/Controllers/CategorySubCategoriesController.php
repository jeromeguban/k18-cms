<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Requests\SubCategoryRequest;

class CategorySubCategoriesController extends Controller
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
    public function index(Category $category)
    {
        $this->authorize('list', SubCategory::class);

        $query = SubCategory::selectedFields()
            ->joinCategory()
            ->withRelations()
            ->searchable()
            ->sortable()
            ->where('sub_categories.category_id', $category->category_id);

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
    public function store(SubCategoryRequest $formRequest, Category $category)
    {
        $this->authorize('create', SubCategory::class);

        $sub_category = $category->addSubCategory(new SubCategory([
            'icon'                     => $formRequest['icon'],
            'sub_category_code'        => strtoupper($formRequest['sub_category_code']),
            'sub_category_name'        => strtoupper($formRequest['sub_category_name']),
            'created_by'               => auth()->id(),
            'modified_by'              => auth()->id(),
        ]));

        activity()
            ->performedOn( $sub_category )
            ->withProperties($sub_category)
            ->log('Sub Category has been created');

        return [
            'success' => 1,
            'data' => $sub_category
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, SubCategory $sub_category)
    {
        $this->authorize('view', $sub_category);

        return SubCategory::where('sub_category_id', $sub_category->sub_category_id)
            ->where('category_id', $category->category_id)
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
    public function update(SubCategoryRequest $formRequest, Category $category, SubCategory $sub_category)
    {
        $this->authorize('update', $sub_category);

        $sub_category->update([
            'icon'                     => $formRequest['icon'],
            'category_id'              => $formRequest['category_id'],
            'sub_category_name'        => strtoupper($formRequest['sub_category_name']),
            'sub_category_code'        => strtoupper($formRequest['sub_category_code']),
            'modified_by' => auth()->id()
        ]);

        activity()
            ->performedOn( $sub_category )
            ->withProperties($sub_category)
            ->log('Sub Category has been updated');

        return [
            'success' => 1,
            'data' => $sub_category
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, SubCategory $sub_category)
    {
        $this->authorize('delete', $sub_category);

        $sub_category->delete();

        activity()
            ->performedOn($sub_category)
            ->withProperties($sub_category)
            ->log('Sub Category has been deleted');

        return [ 'success' => 1 ];
    }
}
