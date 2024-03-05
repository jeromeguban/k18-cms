<?php

namespace App\Http\Controllers;

use App\Models\KeyVisual;
use Illuminate\Http\Request;

class KeyVisualSequenceController extends Controller
{
	public function store(Request $request)
	{
		foreach(request()->sequence as $index => $id) {
			$KeyVisual = KeyVisual::find($id);

			if($KeyVisual){
				$KeyVisual->update([
					'sequence'      => (int)$index + 1
				]);
			}

			activity()
                ->performedOn( $KeyVisual->refresh() )
				->withProperties($KeyVisual->refresh())
                ->log('Key Visual Sequence has been updated');

		}

		return [
            'success'   => 1
        ];
	}

}