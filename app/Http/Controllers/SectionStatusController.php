<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;


class SectionStatusController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $this->authorize('list', Section::class);

	}

    public function update(Request $request, Section $section)
	{
		$section = $section->update([
			'active'  =>  request()->active ? 1 : 0
		]);

		activity()->log('Section Status has been updated');

        return [
            'success'   => 1
        ];
	}
}
