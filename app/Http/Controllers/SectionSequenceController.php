<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionSequenceController extends Controller
{
    public function store(Request $request)
	{

		foreach(request()->sequence as $index => $id) {
			$section = Section::find($id);
			if($section){
				$section->update([
					'sequence'      => $index ? (int)$index + 1 : 0
				]);


			}

			activity()
                ->performedOn( $section->refresh() )
                ->log('Section Sequence has been updated');

		}

		return [
            'success'   => 1
        ];
	}
}
