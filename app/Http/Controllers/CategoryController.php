<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Processes\CategoryProcess;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CategoryRequest;
use App\Processes\CategoryImageProcess;
use App\Http\Requests\CategoryImageRequest;

class CategoryController extends Controller
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
        $this->authorize('list', Category::class);

        $query = Category::withRelations()
                    ->sortable()
                    ->searchable();

            if(request()->sequence == 1){
                $query->orderBy('sequence', 'ASC');
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
    public function store(CategoryRequest $request, CategoryProcess $process)
    {
        $this->authorize('create', Category::class);

        $process->create();

        activity()
            ->performedOn( $process->category() )
            ->withProperties($process->category())
            ->log('Category has been created');

        return [
            'success' => 1,
            'data' => $process->category()
        ];
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return Category::where('category_id', $category->category_id)
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
    public function update(CategoryRequest $request,  Category $category)
    {
        $this->authorize('update', $category);

        $category->update([
            'icon'                 => $request['icon'],
            'category_name'        => strtoupper($request['category_name']),
            'category_code'        => $this->generateCodeSlug($request),
            'featured'             => $request->featured ? 1:0,
            'modified_by'          => auth()->id(),
        ]);

        activity()
            ->performedOn( $category )
            ->withProperties($category)
            ->log('Category has been updated');

        return [
            'success' => 1,
            'data' => $category
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActive(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $category->update([
            'active'              => $request->active == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn( $category )
            ->withProperties($category)
            ->log('Category has been updated');

        return [
            'success' => 1,
            'data' => $category
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setImage(CategoryImageRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        DB::transaction(function () use ($category, $request) {

            if ($request->file('image'))
                (new CategoryImageProcess($category, $request->file('image')))->upload();

            activity()
                ->performedOn($category)
                ->withProperties($category)
                ->log('Category Image has been updated');

            return [
                'success' => 1,
                'data' => $category
            ];
        });
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        activity()
            ->performedOn($category)
            ->log('Category has been deleted');

        return [ 'success' => 1 ];
    }

    private function generateCodeSlug($request)
    {
        $code_slug = Str::kebab(strtolower(str_replace([",", "/" , "@", "%", "&"] , "", $request->category_name))).substr(0, 40);


        return $code_slug;
    }
}
