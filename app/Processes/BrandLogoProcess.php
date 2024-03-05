<?php

namespace App\Processes;

use App\Models\Brand;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class BrandLogoProcess
{
    protected $file;
    protected $brand;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Brand $brand, UploadedFile $file = null)
    {
        $this->brand = $brand;
        $this->file = $file;
        $this->filename = uniqid() . '.png';
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
            ->updateBrandLogo();

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
            ->removeLogo();

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
        $location = public_path('images/brands/' . date('/Y/m/') . $this->filename);

        $location_path = public_path('images/brands' . date('/Y/m/'));

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }

        $image->save($location);

        return $this;
    }

    public function updateBrandLogo()
    {

        $this->brand->update([
            'logo' => '/images/brands' . date('/Y/m/') . $this->filename,
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if ($file = $this->brand->refresh()->logo) {
            Storage::disk('brands')->delete($file);
        }

        return $this;
    }

    public function removeLogo()
    {
        $this->brand->file()->remove();

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('jpg', 75);

        return $image;
    }
}
