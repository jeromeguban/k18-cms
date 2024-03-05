<?php

namespace App\Processes;


use Intervention\Image\Facades\Image as Image;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class TagMobileBannerProcess
{
    protected $file;
    protected $tag;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Tag $tag, UploadedFile $file = null)
    {
        $this->tag  = $tag;
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
            ->removeImageInPublicDirectory()
            ->updateTagBanner();

        return $this;
    }

    /**
     * Execute the upload process.
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
        $image = Image::make($this->file);

        $location = public_path('images/tags/mobile/' . date('Y/m/') . $this->filename);
        $location_path = public_path('images/tags/mobile/' . date('Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }

    public function updateTagBanner()
    {
        if (!Storage::disk('mobile_tags')->exists(date('Y/m/') . $this->filename)) {
            throw new \Exception('File is not uploaded');
        }

        $this->tag->update([
            'mobile_banner'    => 'images/tags/mobile' . date('/Y/m/') . $this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if ($file = $this->tag->refresh()->banner) {
            Storage::disk('mobile_tags')->delete($file);
        }

        return $this;
    }

    public function removeBanner()
    {

        $this->tag->update([
            'mobile_banner'    => null,
        ]);

        return $this;
    }
}
