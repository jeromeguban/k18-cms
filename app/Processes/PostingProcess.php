<?php

namespace App\Processes;

use App\Models\Posting;
use App\Models\PostingFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostingProcess
{

    protected $request, $posting, $categories = [] ?? null, $sub_categories = [] ?? null, $brands = [] ?? null;

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
     * Execute find process.
     *
     * @return void
     */

    public function find($id)
    {

        $this->posting = Posting::findOrFail($id);

        return $this;

    }

    /**
     * Retrive posting.
     *
     * @return
     */

    public function posting()
    {

        return $this->posting;

    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->createPosting();
            // $this->createPostingFilterCategories();
            // $this->createPostingFilterSubCategories();
            // $this->createPostingFilterBrands();

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

            $this->updatePosting();
            // $this->deletePostingFilter();
            // $this->createPostingFilterCategories();
            // $this->createPostingFilterSubCategories();
            // $this->createPostingFilterBrands();

        });

    }

    /**
     * Create posting.
     *
     * @return void
     */

    private function createPosting()
    {
        $this->posting = Posting::create([
            'slug' => $this->generateSlug(),
            'category' => $this->request->posting_type,
            'quantity' => 1,
            'item_category_type' => $this->request->item_category_type,
            'categories' => json_encode($this->request->categories),
            'sub_categories' => $this->request->sub_categories == null ? null : json_encode($this->request->sub_categories),
            'brands' => json_encode($this->request->brands),
            'name' => $this->request->name,
            'description' => $this->request->description,
            'extended_description' => $this->request->extended_description,
            'location' => $this->request->location,
            'unit_price' => $this->request->unit_price ? $this->request->unit_price : 0,
            // 'store_id'                  => session()->get('store_id'),
            'viewing_details' => is_array($this->request->viewing_details) ? json_encode($this->request->viewing_details) : str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->viewing_details)))),
            'category_sequence' => $this->request->posting_type == 'Wholesale' ? 3 : ($this->request->posting_type == 'International Trade' ? 5 : 4),
            'created_by' => auth()->user()->id,
            'modified_by' => auth()->user()->id,
        ]);

        return $this;

    }

    /**
     * Update posting.
     *
     * @return void
     */

    private function updatePosting()
    {
        $this->posting->update([
            'category' => $this->request->category,
            'item_category_type' => $this->request->item_category_type,
            'quantity' => 1,
            'categories' => json_encode($this->request->categories) ,
            'sub_categories' => $this->request->sub_categories == null ? null : json_encode($this->request->sub_categories),
            'brands' => json_encode($this->request->brands),
            'name' => $this->request->name,
            'description' => $this->request->description,
            'extended_description' => $this->request->extended_description,
            'location' => $this->request->location,
            // 'store_id'                  => session()->get('store_id'),
            'viewing_details' => $this->request->viewing_details ? str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->viewing_details)))) : null,
            'unit_price' => $this->request->unit_price ? $this->request->unit_price : 0,
            'category_sequence' => $this->request->posting_type == 'Wholesale' ? 3 : ($this->request->posting_type == 'International Trade' ? 5 : 4),
            'modified_by' => auth()->id(),
        ]);

        return $this;

    }

    public function generateSlug()
    {
        $slug = str_replace("\\", "", str_replace("/", "", substr(Str::kebab(strtolower($this->request->name)), 0, 35)));

        $valid_update = false;
        $posting_slug_exists = Posting::where('slug', $slug)->first();

        if ($this->posting) {
            $valid_update = $this->posting->posting_id == $posting_slug_exists->posting_id;
        }

        if ($posting_slug_exists && !$valid_update) {
            $slug = $slug . '-' . uniqid(5);
        }

        return $slug;

    }

    private function createPostingFilterCategories()
    {

        $this->categories = $this->request->categories;

        if ($this->request->sub_categories) {

            foreach ($this->categories as $category) {

                PostingFilter::updateOrCreate([

                    'posting_id' => $this->posting->posting_id,
                    'filter_type' => "categories",
                    'filter_id' => $category,

                ], [
                    'created_by' => 1,
                    'published_date' => null,
                ]);
            }
        }
    }

    private function createPostingFilterSubCategories()
    {

        $this->sub_categories = $this->request->sub_categories;

        if ($this->request->sub_categories) {

            foreach ($this->sub_categories as $sub_category) {

                PostingFilter::updateOrCreate([

                    'posting_id' => $this->posting->posting_id,
                    'filter_type' => "sub_categories",
                    'filter_id' => $sub_category,

                ], [
                    'created_by' => 1,
                    'published_date' => null,
                ]);
            }
        }

    }

    private function createPostingFilterBrands()
    {

        $this->brands = $this->request->brands;

        if ($this->request->sub_categories) {

            foreach ($this->brands as $brand) {

                PostingFilter::updateOrCreate([

                    'posting_id' => $this->posting->posting_id,
                    'filter_type' => "brands",
                    'filter_id' => $brand,

                ], [
                    'created_by' => 1,
                    'published_date' => null,
                ]);
            }
        }
    }

    private function deletePostingFilter()
    {

        $posting_filters = PostingFilter::where('posting_id', $this->posting->posting_id)->get();

        if ($posting_filters->count() > 0) {

            PostingFilter::where('posting_id', $this->posting->posting_id)->delete();
        }
    }

}
