<?php

namespace App\Processes;


use Intervention\Image\Facades\Image as Image;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class StoreBannerProcess
{
	protected $file;
	protected $store;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Store $store, UploadedFile $file = null)
    {
        $this->store  = $store;
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
     			->updateStoreBanner();

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

     	$location = public_path('images/stores/banner/'.date('Y/m/').$this->filename);
     	$location_path = public_path('images/stores/banner'.date('Y/m/'));
     	if (!file_exists($location_path)) {
     		mkdir($location_path, 0755 , true);
     	}
     	$image->save($location);

     	return $this;
     }

     public function updateStoreBanner()
     {
     	// if (! Storage::disk('stores')->exists(date('Y/m/').$this->filename)) {
     	// 		throw new \Exception('File is not uploaded');
     	// }

     	$this->store->update([
     		'banner'	=> '/images/stores/banner'.date('/Y/m/').$this->filename
     	]);

     	return $this;
     }

     public function removeImageInPublicDirectory()
     {
     	if($file = $this->store->refresh()->banner) {
            Storage::disk('stores')->delete($file);
        }

        return $this;
     }

     public function removeBanner()
     {

     	$this->store->update([
     		'banner'	=> null,
     	]);

     	return $this;
     }
}
