<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use Illuminate\Http\Request;

class QuickLinkStatusController extends Controller
{
    public function update(Request $request, QuickLink $quicklink)
	{
		$quicklink = $quicklink->update([
			'active'  =>  request()->active
		]);

		activity()->log('Quick Link Status has been updated');

        return [
            'success'   => 1
        ];
	}
}
