<?php

namespace App\Processes;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileProcess
{
    protected $file, $model, $filename, $request;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(File $model, UploadedFile $file = null, $request = null)
    {
        $this->request = $request ? (object) $request : request();

        $this->model = $model;
        $this->file = $file;

        $this->filename = $file->getClientOriginalName();
    }

    /**
     * Execute the upload process.
     *
     * @return void
     */
    public function upload()
    {

        $this->uploadFile()
            ->createFileRecord();

        return $this;
    }

    /**
     * Execute the remove process.
     *
     * @return void
     */
    public function remove()
    {
        $this->removeFileInPublicDirectory()
            ->removeFile();

        return $this;
    }
    public function filename()
    {
        return $this->filename;
    }

    public function uploadFile()
    {

        $location_path = public_path('files/');

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $this->file->storeAs($this->filename, '', 'files');

        return $this;
    }

    public function createFileRecord()
    {

        $this->model->create([
            'subject_type' => "Inquiry",
            'subject_id' => $this->request->id,
            'file' => $this->filename,
            'created_by' => auth()->user()->id,
            'modified_by' => auth()->user()->id,
        ]);

        return $this;
    }

    public function removeFileInPublicDirectory()
    {
        if ($file = $this->model->refresh()->banner) {
            Storage::disk('files')->delete($file);
        }

        return $this;
    }

    public function removeFile()
    {
        $this->model->remove();

        return $this;
    }

}
