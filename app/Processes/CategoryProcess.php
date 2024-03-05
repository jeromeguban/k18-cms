<?php

namespace App\Processes;

use App\Models\Category;
use App\Processes\CategoryImageProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

class CategoryProcess
{
    protected $request, $category;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = $request ? (object) $request : request();
    }
    /**
     * Retrive Adds.
     *
     * @return
     */

    public function find($id)
    {

        $this->category = Category::findOrFail($id);

        return $this;
    }

    public function category()
    {
        return $this->category;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->createCategory()
                ->saveImage();
        });
    }

    /**
     * Execute update process.
     *
     * @return void
     */

    public function update()
    {

        DB::transaction(function () {
            $this->updateCategory();
        });
    }

    public function createCategory()
    {
        $this->category = Category::create([
            'icon'                  => $this->request->icon,
            'category_name'         => strtoupper($this->request->category_name),
            'category_code'         => $this->generateCodeSlug($this->request),
            'featured'              => $this->request->featured ? 1 : 0,
            'color'                 => $this->request->color,
            'created_by'            => auth()->id(),
            'modified_by'           => auth()->id(),
        ]);

        return $this;
    }

    private function updateCategory()
    {
    }

    private function saveImage()
    {

        (new CategoryImageProcess($this->category, $this->request->file('image')))->upload();

        return $this;
    }

    private function generateCodeSlug($request)
    {
        $code_slug = Str::kebab(strtolower(str_replace([",", "/", "@", "%", "&"], "", $request->category_name))) . substr(0, 40);

        return $code_slug;
    }
}
