<?php

namespace App\Http\Controllers;

use App\Http\Requests\NavigationRequest;
use App\Models\Navigation;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', Navigation::class);

        $query = Navigation::selectedFields()
            ->whereQueryScopes()
            ->orderBy('sequence', 'ASC')
            ->get();

        return $query;

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NavigationRequest $request)
    {
        $this->authorize('create', Navigation::class);

        $navigation = Navigation::create([
            'type' => $request->type,
            'label' => $request->label,
            'link' => $request->link,
            'icon' => $request->icon,
            'properties' => $request->type == 'Footer' ? json_encode($request->properties) : json_encode([]),
            'sequence' => Navigation::getLastSequence($request->type),
            'created_by' => auth()->user()->id,
            'modified_by' => auth()->user()->id,
        ]);

        activity()
            ->performedOn($navigation)
            ->withProperties($navigation)
            ->log('Navigation has been created');

        return [
            'success' => 1,
            'data' => $navigation,
        ];
    }

    public function update(NavigationRequest $request, Navigation $navigation)
    {
        $navigation->update([
            'type' => $request->type,
            'label' => $request->label,
            'link' => $request->link,
            'icon' => $request->icon,
            'properties' => json_encode($request->properties) ?? null,
            'modified_by' => auth()->user()->id,
        ]);

        activity()
            ->performedOn($navigation)
            ->withProperties($navigation)
            ->log('Navigation has been updated');

        return [
            'success' => 1,
            'data' => $navigation,
        ];
    }

    public function destroy(Navigation $navigation)
    {
        $this->authorize('delete', $navigation);

        $navigation->delete();

        activity()
            ->performedOn($navigation)
            ->withProperties($navigation)
            ->log('Navigation has been deleted');

        return ['success' => 1];
    }

    public function updateSequence(Request $request)
    {
        foreach (request()->sequence as $index => $id) {
            $navigation = Navigation::find($id);

            if ($navigation) {
                $navigation->update([
                    'sequence' => (int) $index + 1,
                ]);
            }

            activity()
                ->performedOn($navigation->refresh())
                ->withProperties($navigation->refresh())
                ->log('Navigation Sequence has been updated');

        }
    }

    public function updatePropertySequence(Request $request)
    {
        $navigation = Navigation::where('id', $request->navigation['id'])
            ->first();

        if ($navigation) {
            $navigation->update([
                'properties' => json_encode($request->navigation['property']),
            ]);
        }

        activity()
            ->performedOn($navigation->refresh())
            ->withProperties($navigation->refresh())
            ->log('Property Sequence has been updated');

        return [
            'success' => 1,
            'data' => $navigation,
        ];
    }
}
