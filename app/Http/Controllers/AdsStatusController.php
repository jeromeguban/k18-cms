<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;

class AdsStatusController extends Controller
{
    public function update(Request $request, Ads $ad)
	{
		$ad = $ad->update([
			'active'  =>  request()->active
		]);

		activity()
		->withProperties($ad)
		->log('Ads Status has been updated');

        return [
            'success'   => 1
        ];
	}

	public function updateFeatured(Request $request, Ads $ad)
	{
		$currently_featured = Ads::where('featured', 1)->first();

		if($currently_featured){
            $currently_featured->update([
                'featured'  => 0
            ]);
        }

		$ad = $ad->update([
			'featured'	=> $request->featured
		]);


		activity()
			->withProperties($ad)
			->log('Ads Featured status has been updated');

		return [
			'success'	=> 1
		];
	}
}
