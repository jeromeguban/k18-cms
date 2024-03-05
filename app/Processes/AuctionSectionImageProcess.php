<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\AuctionSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class AuctionSectionImageProcess
{
    protected $file;
    protected $location;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(AuctionSection $auction_section, UploadedFile $file = null)
    {
        $this->file             = $file;
        $this->filename         = uniqid() . '.jpg';
    }

    /**
     * Execute the upload process.
     *
     * @return void
     */
    public function upload()
    {
        $this->uploadImage();

        return  $this->filename;
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

    public function uploadImage()
    {
        // Saving.
        $image = Image::make($this->file);

        $this->location = public_path('images/auction_sections/' . $this->filename);
        $location_path = public_path('images/auction_sections/');
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($this->location);

        return $this;
    }
}
