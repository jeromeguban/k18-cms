<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\KeyVisual;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
class KeyVisualMobileBannerProcess
{
	protected $file;
	protected $posting;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(KeyVisual $keyVisual, UploadedFile $file = null)
    {
        $this->keyVisual  = $keyVisual;
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
            // ->removeImageInPublicDirectory()
            ->updateKeyVisualBanner();

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
        $image = $this->optimize($image);

        $location = public_path('images/key_visuals/mobile/'.date('/Y/m/').$this->filename);
        $location_path = public_path('images/key_visuals/mobile'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }
    public function updateKeyVisualBanner()
    {
        if(! Storage::disk('mobile_key_visuals')->exists(date('/Y/m/').$this->filename)) {
            throw new \Exception('File is not uploaded');
        }


        $this->keyVisual->update([
            'mobile_banner'  => '/images/key_visuals/mobile'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if($file = $this->keyVisual->refresh()->banner) {
            Storage::disk('mobile_key_visuals')->delete($file);
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
        $image->fit(412 , 247 );


        return $image;
    }
}
