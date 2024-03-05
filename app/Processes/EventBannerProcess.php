<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class EventBannerProcess
{
	protected $file;
	protected $event;
	protected $filename;

	/**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Event $event, UploadedFile $file = null, $request = null)
    {
        $this->event  = $event;
        $this->file     = $file;
        $this->filename = $this->file == null ? '' : uniqid() .'.'. $this->file->extension();
		$this->request = $request ? (object) $request : request();

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
            ->updateEventBanner();

        return $this;
    }

    public function filename()
    {
        return $this->filename;
    }

    public function uploadImage()
    {

        $image = Image::make($this->file);

        $location = public_path('images/events/'.date('Y/m/').$this->filename);
        // $location = 'images/events/'.date('Y/m/').$this->filename;
        $location_path = public_path('images/events/'.date('Y/m/'));

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755 , true);
        }

        $image->save($location);

        return $this;
    }

    public function removeImageInPublicDirectory()
    {
    	if($this->request->image_name == $this->event->banner)
            $this->event->update([
                'banner' => null
            ]);


        return $this;
    }

    public function updateEventBanner()
    {
    	$this->event->update([
    		'banner'	=> '/images/events'.date('/Y/m/').$this->filename
    	]);


    	return $this;
    }
}