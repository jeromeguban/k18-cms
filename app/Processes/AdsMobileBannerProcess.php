<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Ads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class AdsMobileBannerProcess
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

        $location = public_path('images/ads/mobile/'.date('/Y/m/').$this->filename);
        $location_path = public_path('images/ads/mobile'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }

    public function updateAdsBanner()
    {
        if(! Storage::disk('mobile_ads')->exists(date('/Y/m/').$this->filename)) {
            throw new \Exception('File is not uploaded');
        }


        $this->ads->update([
            'mobile_banner'  => '/images/ads/mobile'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if($file = $this->ads->refresh()->banner) {
            Storage::disk('mobile_ads')->delete($file);
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
        $image->fit(412 , 180);

        return $image;
    }

}
