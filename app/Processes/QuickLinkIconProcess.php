<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\QuickLink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class QuickLinkIconProcess
{
	protected $file;
	protected $quickLink;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(QuickLink $quickLink, UploadedFile $file = null)
    {
        $this->quickLink  = $quickLink;
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
        $this->uploadIcon()
            ->updateQuickLinkIcon();

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
            ->removeIcon();

        return $this;
    }
    public function filename()
    {
        return $this->filename;
    }
     public function uploadIcon()
    {
        // Saving and optimizing file.
        $image = Image::make($this->file);
        // $image = $this->optimize($image);

        $location = public_path('images/icons/'.date('/Y/m/').$this->filename);
        $location_path = public_path('images/icons/'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }

    public function updateQuickLinkIcon()
    {
        // if(! Storage::disk('ads')->exists($this->filename)) {
        //     throw new \Exception('File is not uploaded');
        // }


        $this->quickLink->update([
            'icon'  => '/images/icons'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    public function removeIconInPublicDirectory()
    {
        if($file = $this->quickLink->refresh()->icon) {
            Storage::disk('icons')->delete($file);
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
        $image->fit(800 , 508 );

        return $image;
    }

}
