<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Processes\FileProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
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
        $this->authorize('list', File::class);

        $query = File::selectedFields()
            ->withRelations()
            ->searchable()
            ->sortable();

        if (request()->subject_id && request()->subject_type) {
            $query->where('subject_id', request()->subject_id)
                ->where('subject_type', request()->subject_type);
        }

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, File $file)
    {

        $process = new FileProcess($file, $request->file('file'));
        $process->upload();

        activity()
            ->performedOn($file)
            ->withProperties($file)
            ->log('File Successfuly Uploaded');

        return [
            'success' => 1,
            'data' => $file->refresh(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {

        $this->authorize('update', $file);

        $file->update([
            'modified_by' => auth()->id(),
        ]);

        activity()
            ->performedOn($file)
            ->withProperties($file)
            ->log('File has been updated');

        return [
            'success' => 1,
            'data' => $file,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $this->authorize('delete', $file);
        DB::transaction(function () use ($file) {

            $file->delete();

            activity()
                ->performedOn($file)
                ->withProperties($file)
                ->log('File has been deleted');
        });

        return [
            'success' => 1,
        ];
    }
}
