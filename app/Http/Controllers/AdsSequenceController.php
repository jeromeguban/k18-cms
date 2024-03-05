<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;

class AdsSequenceController extends Controller
{
    public function store(Request $request)
	{
		foreach(request()->sequence as $index => $id) {
			$ad = Ads::find($id);

			if($ad){
				$ad->update([
					'sequence'      => (int)$index + 1
				]);
			}

			activity()
                ->performedOn( $ad->refresh() )
				->withProperties($ad->refresh())
                ->log('Ads Sequence has been updated');

		}

		return [
            'success'   => 1
        ];
	}
}
