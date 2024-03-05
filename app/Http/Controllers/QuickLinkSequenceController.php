<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use Illuminate\Http\Request;

class QuickLinkSequenceController extends Controller
{
    public function store(Request $request)
	{
		foreach(request()->sequence as $index => $id) {
			$quicklink = QuickLink::find($id);

			if($quicklink){
				$quicklink->update([
					'sequence'      => (int)$index + 1
				]);
			}

			activity()
                ->performedOn( $quicklink->refresh() )
				->withProperties($quicklink->refresh())
                ->log('Quick Link Sequence has been updated');

		}

		return [
            'success'   => 1
        ];
	}
}
