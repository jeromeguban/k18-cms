<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Ads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class AdsBannerProcess
{
	protected $file;
	protected $ads;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Ads $ads, UploadedFile $file = null)
    {
        $this->ads  = $ads;
        $this->file   = $file;
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
            ->updateAdsBanner();

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
            ->removeBanner();

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

        $location = public_path('images/ads/' . date('/Y/m/') . $this->filename);
        $location_path = public_path('images/ads'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }

    public function updateAdsBanner()
    {
        // if(! Storage::disk('ads')->exists($this->filename)) {
        //     throw new \Exception('File is not uploaded');
        // }


        $this->ads->update([
            'banner'  => '/images/ads'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if($file = $this->ads->refresh()->banner) {
            Storage::disk('ads')->delete($file);
        }

        return $this;
    }

    public function removeBanner()
    {
        $this->posting->file()->remove();

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('jpg', 75);
        if($this->ads->ads_size == 'w-full'){
            $image->fit(1280, 324);
        }
        if($this->ads->ads_size == 'w-3/4'){
            $image->fit(954, 324);
        }
        if($this->ads->ads_size == 'w-2/3'){
            $image->fit(848, 324);
        }
        if($this->ads->ads_size == 'w-1/2'){
            $image->fit(636, 324);
        }
        if($this->ads->ads_size == 'w-1/3'){
            $image->fit(424, 324);
        }
        if($this->ads->ads_size == 'w-1/4'){
            $image->fit(318, 324);
        }

        return $image;
    }

}
