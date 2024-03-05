<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategorySequenceController extends Controller
{
    public function store(Request $request)
    {
        foreach (request()->sequence as $index => $id) {
            $category = Category::find($id);

            if ($category) {
                $category->update([
                    'sequence'      => (int)$index + 1
                ]);
            }

            activity()
                ->performedOn($category->refresh())
                ->withProperties($category->refresh())
                ->log('Category Sequence has been updated');
        }

        return [
            'success'   => 1
        ];
    }
}
