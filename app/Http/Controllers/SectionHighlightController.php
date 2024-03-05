<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionHighlightController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
	    $this->authorize('list', Section::class);

	    $query = Section::withRelations()
	        ->whereQueryScopes()
	        ->where('highlight', $request->highlight)
	        ->searchable()
	        ->orderBy('sequence','ASC');

	    return $this->response($query);
	}

    public function update(Request $request, Section $section)
	{
		$section = $section->update([
			'highlight'  =>  request()->highlight ? 1 : 0
		]);

		activity()->log('Section Highlight has been updated');

        return [
            'success'   => 1
        ];
	}
}
