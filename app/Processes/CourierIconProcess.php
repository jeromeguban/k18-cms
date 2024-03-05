<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Courier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class CourierIconProcess
{
    protected $file;
    protected $courier;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Courier $courier, UploadedFile $file = null)
    {
        $this->courier    = $courier;
        $this->file       = $file;
        $this->filename   = uniqid() . '.jpg';
    }

    /**
     * Execute the upload process.
     *
     * @return void
     */
    public function upload()
    {
        $this->uploadImage()
            // ->removeImageInPublicDirectory()
            ->updateCouriericon();

        return $this;
    }

    /**
     * Execute the remove process.
     *
     * @return void
     */
    public function remove()
    {
        $this->removeImageInPublicDirectory()
            ->removeicon();

        return $this;
    }

    public function filename()
    {
        return $this->filename;
    }

    public function uploadImage()
    {
        // Saving and optimizing file.
        $image = Image::make($this->file);
        // $image = $this->optimize($image);

        $location = public_path('images/couriers/' . date('/Y/m/') . $this->filename);

        $location_path = public_path('images/couriers' . date('/Y/m/'));

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }

        $image->save($location);

        return $this;
    }

    public function updateCouriericon()
    {

        $this->courier->update([
            'icon'  => '/images/couriers' . date('/Y/m/') . $this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if ($file = $this->courier->refresh()->icon) {
            Storage::disk('couriers')->delete($file);
        }

        return $this;
    }

    public function removeicon()
    {
        $this->courier->file()->remove();

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('jpg', 75);
       
        return $image;
    }
}
