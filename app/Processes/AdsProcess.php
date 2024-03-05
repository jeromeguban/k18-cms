<?php

namespace App\Processes;

use App\Models\Ads;
use App\Processes\AdsBannerProcess;
use App\Processes\AdsMobileBannerProcess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdsProcess
{
    protected $request, $ads;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {

        $this->request = $request ? (object) $request : request();
    }
    /**
     * Retrive Adds.
     *
     * @return 
     */

    public function find($id)
    {

        $this->ads = Ads::findOrFail($id);

        return $this;
    }

    public function ads()
    {
        return $this->ads;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->createAds()
                ->saveBanner()
                ->saveMobileBanner();
        });
    }

    /**
     * Execute update process.
     *
     * @return void
     */

    public function update()
    {

        DB::transaction(function () {
            $this->updateAds();
        });
    }

    public function createAds()
    {
        $last_item = Ads::count() ? Ads::orderBy('sequence', 'desc')->first() : 0;

        $currently_featured = Ads::where('featured', 1)->first();

        if ($currently_featured) {
            $currently_featured->update([
                'featured'  => 0
            ]);
        }

        $this->ads = Ads::create([
            'orientaion'    => $this->request->orientation ?? 'Portrait',
            'ads_name'      => $this->request->ads_name,
            'ads_link'      => $this->request->ads_link,
            'sequence'      => $last_item ? $last_item->sequence + 1 : $last_item + 1,
            'featured'      => $this->request->featured ? 1 : 0,
            'created_by'    => auth()->id(),
            'modified_by'   => auth()->id(),
        ]);

        return $this;
    }

    private function updateAds()
    {

        $this->ads->update([
            // 'orientaion'      => $this->request->orientation,
            'ads_name'       => $this->request->ads_name,
            'ads_link'       => $this->request->ads_link,
            'modified_by'    => auth()->id(),
        ]);

        return $this;
    }

    private function saveBanner()
    {
        (new AdsBannerProcess($this->ads, $this->request->file('banner')))->upload();

        return $this;
    }

    private function saveMobileBanner()
    {

        (new AdsMobileBannerProcess($this->ads, $this->request->file('mobile_banner')))->upload();

        return $this;
    }
}
