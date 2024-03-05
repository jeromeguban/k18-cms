<?php

namespace App\Processes;

use Intervention\Image\Facades\Image as Image;
use App\Models\Auction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use App\Models\Searchable\Auction as SearchableAuction;

class CatalogFileUploadProcess
{
    protected $file;
    protected $auction;
    protected $location;
    protected $filename;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct(Auction $auction, UploadedFile $file = null)
    {
        $this->auction          = $auction;
        $this->file             = $file;
        $this->filename         = uniqid() . '.pdf';
    }

    /**
     * Execute the upload process.
     *
     * @return void
     */
    public function upload()
    {
        $this->uploadFile()
            ->updateAuctionCatagol();

        return  $this;
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

    public function uploadFile()
    {
        // Saving.
        $file = $this->file; // Assuming $this->file contains the uploaded file

        $this->location = public_path('files/' . $this->filename);
        $location_path = public_path('files/');

        if (!file_exists($location_path)) {
            mkdir($location_path, 0755, true);
        }

        $file->move($location_path, $this->filename);

        return $this;
    }

    public function updateAuctionCatagol()
    {
        $this->auction->update([
            'catalog'   => $this->filename
        ]);

        SearchableAuction::where('auction_id', $this->auction->auction_id)->searchable();

        return $this;
    }
}
