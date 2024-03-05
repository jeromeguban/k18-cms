<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class CategoryImageProcess
{
	protected $file;
	protected $category;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Category $category, UploadedFile $file = null)
    {
        $this->category     = $category;
        $this->file         = $file;
        $this->filename     = uniqid() . '.jpg';
    }

    /**
     * Execute the upload process.
     *
     * @return void
    */
    public function upload()
    {
        $this->uploadImage()
            ->updateCategoryImage();

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

    public function uploadImage()
    {
        // Saving and optimizing file.
        $image = Image::make($this->file);
        // $image = $this->optimize($image);

        $location = public_path('images/redesign/categories/'.date('/Y/m/').$this->filename);
        $location_path = public_path('images/redesign/categories/'.date('/Y/m/'));
        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }
        $image->save($location);

        return $this;
    }

    public function updateCategoryImage()
    {
        // if(! Storage::disk('ads')->exists($this->filename)) {
        //     throw new \Exception('File is not uploaded');
        // }


        $this->category->update([
            'image'  => '/images/redesign/categories/'.date('/Y/m/').$this->filename
        ]);

        return $this;
    }

    public function removeIconInPublicDirectory()
    {
        if($file = $this->category->refresh()->icon) {
            Storage::disk('categorys')->delete($file);
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
