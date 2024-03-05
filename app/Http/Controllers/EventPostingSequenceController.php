<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\Elastic\Posting as SearchablePosting;
use Illuminate\Http\Request;

class EventPostingSequenceController extends Controller
{
    public function store(Request $request)
    {
        foreach (request()->sequence as $index => $id) {
            $posting = Posting::find($id);

            if ($posting) {
                $posting->update([
                    'sequence'      => (int)$index + 1
                ]);
            }

            SearchablePosting::where('posting_id', $posting->posting_id)->searchable();

            activity()
                ->performedOn($posting->refresh())
                ->withProperties($posting->refresh())
                ->log('Event Posting Sequence has been updated');
        }

        return [
            'success'   => 1
        ];
    }
}
