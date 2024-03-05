<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Section;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class SectionMobileBannerProcess
{
	protected $file;
    protected $section;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Section $section, UploadedFile $file = null)
    {
        $this->section  = $section;
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
            ->updateSectionBanner();

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

        $location = public_path('images/sections/mobile/'.date('/Y/m/').$this->filename);
        $location_path = public_path('images/sections/mobile'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }

    public function updateSectionBanner()
    {
        // if(! Storage::disk('mobile_section')->exists(date('/Y/m/').$this->filename)) {
        //     throw new \Exception('File is not uploaded');
        // }


        $this->section->update([
            'mobile_banner'  => '/images/sections/mobile'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('jpg', 75);
        $image->fit(412 , 180);

        return $image;
    }
}
