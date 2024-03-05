<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Career;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
class CareerBannerProcess
{
	protected $file;
	protected $posting;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Career $career, UploadedFile $file = null)
    {
        $this->career  = $career;
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
            ->updateCareerBanner();

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

        $location = public_path('images/careers/'.date('/Y/m/').$this->filename);
        $location_path = public_path('images/careers'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }
    public function updateCareerBanner()
    {
        // if(! Storage::disk('key_visuals')->exists(date('/Y/m/').$this->filename)) {
        //     throw new \Exception('File is not uploaded');
        // }


        $this->career->update([
            'banner'  => '/images/careers'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if($file = $this->career->refresh()->banner) {
            Storage::disk('careers')->delete($file);
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
        // $image->fit(1015, 365);


        return $image;
    }
}
