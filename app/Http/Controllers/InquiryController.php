<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $this->authorize('list', Inquiry::class);

        $query = Inquiry::searchable()
            ->sortable();

        return $this->response($query);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inquiry  $Inquiry
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Inquiry::findOrFail($id));

        return Inquiry::where('id', $id)
            ->withRelations()
            ->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inquiry  $Inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inquiry $Inquiry)
    {
        $this->authorize('delete', $Inquiry);

        $Inquiry->delete();
        activity()
            ->performedOn($Inquiry)
            ->log('Inquiry has been deleted');

        return ['success' => 1];
    }
}
