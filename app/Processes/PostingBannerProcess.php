<?php

namespace App\Processes;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

use Intervention\Image\Facades\Image as Image;
use App\Models\Posting;
use App\Models\Searchable\Posting as SearchablePosting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
class PostingBannerProcess
{
	protected $file, $posting, $filename, $request;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Posting $posting, UploadedFile $file = null, $request = null)
    {
        $this->posting  = $posting;
        $this->file     = $file;
        $this->filename = $this->file == null ? '' : uniqid() .'.'. $this->file->extension();
		$this->request  = $request ? (object) $request : request();

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
            ->updatePostingBanner();

        return $this;
    }

    /**
     * Execute the remove process.
     *
     * @return void
    */
    public function remove()
    {
        $this->removeImageInPublicDirectory();

        return $this;
    }

    public function filename()
    {
        return $this->filename;
    }

    public function uploadImage()
    {

        $image = Image::make($this->file);

        $location = public_path('images/postings/'.date('Y/m/').$this->filename);
        // $location = 'images/postings/'.date('Y/m/').$this->filename;
        $location_path = public_path('images/postings/'.date('Y/m/'));

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755 , true);
        }

        $image->save($location);

        return $this;
    }

    public function updatePostingBanner()
    {
        // if (! Storage::disk('postings')->exists(date('Y/m/').$this->filename)) {
        //     throw new \Exception('File is not uploaded');
        // }

        if($this->posting->images != null)
            $images = array_merge(json_decode($this->posting->images, true), array('/images/postings/'.date('Y/m/').$this->filename));

        $this->posting->update([
            'images' => $this->posting->images ? str_replace("\\/", "/", json_encode($images)) : str_replace("\\/", "/", json_encode(array('/images/postings/'.date('Y/m/').$this->filename))),
            'banner' => '/images/postings/'.date('/Y/m/').$this->filename
        ]);

        SearchablePosting::where('posting_id', $this->posting->posting_id)->searchable();  

        return $this;
    }

    public function removeImageInPublicDirectory()
    {

        if($this->request->image_name == $this->posting->banner)
            $this->posting->update([
                'banner' => null
            ]);

        $images = $this->posting->images;

        if(file_exists(public_path($this->request->image_name)))
            unlink(public_path($this->request->image_name));

        $result = array_diff(json_decode($images, true), array($this->request->image_name));

        $this->posting->update([
            'images' => json_encode($result)
        ]);

        return $this;
    }

    private function optimize($image)
    {
        $image->encode('jpg', 60);
        // $image->fit(548, 300);

        return $image;
    }
}
