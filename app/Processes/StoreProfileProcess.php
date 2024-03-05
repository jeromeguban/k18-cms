<?php

namespace App\Processes;

use App\Models\Store;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class StoreProfileProcess
{
    protected $file;
    protected $store;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Store $store, UploadedFile $file = null)
    {
        $this->store = $store;
        $this->file = $file;
        $this->filename = uniqid() . '.jpg';

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
            ->updateStoreProfile();

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
        $location = public_path('images/stores/profile' . date('/Y/m/') . $this->filename);

        $location_path = public_path('images/stores/profile' . date('/Y/m/'));

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }

        $image->save($location);

        return $this;
    }

    public function updateStoreProfile()
    {

        $this->store->update([
            'profile' => '/images/stores/profile' . date('/Y/m/') . $this->filename,
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if ($file = $this->store->refresh()->profile) {
            Storage::disk('stores')->delete($file);
        }

        return $this;
    }

    public function removeLogo()
    {
        $this->store->file()->remove();

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('jpg', 75);

        return $image;
    }
}
