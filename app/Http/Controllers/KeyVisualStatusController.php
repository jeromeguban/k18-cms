<?php

namespace App\Http\Controllers;

use App\Models\KeyVisual;
use Illuminate\Http\Request;

class KeyVisualStatusController extends Controller
{
	public function update(Request $request, KeyVisual $keyVisual)
	{
		$keyVisual = $keyVisual->update([
			'active'  =>  request()->active
		]);

		activity()->log('Key Visual Status has been updated');

        return [
            'success'   => 1
        ];
	}

}