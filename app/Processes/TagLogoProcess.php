<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class TagLogoProcess
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
        $this->tag        = $tag;
        $this->file       = $file;
        $this->filename   = uniqid() . '.png';
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
            ->updateTagLogo();

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
        $location = public_path('images/tags/' . date('/Y/m/') . $this->filename);

        $location_path = public_path('images/tags' . date('/Y/m/'));

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }

        $image->save($location);

        return $this;
    }

    public function updateTagLogo()
    {

        $this->tag->update([
            'logo'  => '/images/tags' . date('/Y/m/') . $this->filename
        ]);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
        if ($file = $this->tag->refresh()->logo) {
            Storage::disk('tags')->delete($file);
        }

        return $this;
    }

    public function removeLogo()
    {
        $this->tag->file()->remove();

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('png', 75);

        return $image;
    }
}
