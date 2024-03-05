<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryStatusController extends Controller
{
    public function updateStatus(Request $request, Category $category)
    {
        $category = $category->update([
            'active'  =>  request()->active
        ]);

        activity()
            ->withProperties($category)
            ->log('Category Status has been updated');

        return [
            'success'   => 1
        ];
    }

    public function updateFeatured(Request $request, Category $category)
    {
        $currently_featured = Category::where('featured', 1)->first();

        if ($currently_featured) {
            $currently_featured->update([
                'featured'  => 0
            ]);
        }

        $category = $category->update([
            'featured'    => $request->featured
        ]);


        activity()
            ->withProperties($category)
            ->log('Categories Featured has been updated');

        return [
            'success'    => 1
        ];
    }
}
