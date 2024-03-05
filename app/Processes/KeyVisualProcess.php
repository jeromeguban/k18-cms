<?php
namespace App\Processes;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

use Carbon\Carbon;
use App\Models\KeyVisual;
use Illuminate\Support\Facades\DB;
use App\Processes\KeyVisualBannerProcess;
use App\Processes\KeyVisualMobileBannerProcess;

class KeyVisualProcess
{
	protected $request, $keyVisual;


	 /**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{

		$this->request = $request ? (object) $request : request();

	}

    public function find($id)
    {

        $this->keyVisual = KeyVisual::findOrFail($id);

        return $this;

    }


    /**
     * Retrive Brand.
     *
     * @return
    */

    public function keyVisual()
    {

        return $this->keyVisual;

    }


    public function create()
    {
        DB::transaction(function(){

           $this->createKeyVisual()
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

        DB::transaction(function(){
            $this->updateKeyVisual();
        });

    }

    private function createKeyVisual()
    {
        $last_item = KeyVisual::count() ? KeyVisual::orderBy('sequence', 'desc')->first() : 0;

        $this->keyVisual = KeyVisual::create([
            'link'            => $this->request->link ?? null,
            'name'            => $this->request->name ?? null,
            'sequence'        => $last_item ? $last_item->sequence + 1 : $last_item + 1 ,
            'created_by'      => auth()->id(),
            'modified_by'      => auth()->id(),
        ]);

        return $this;
    }

    private function updateKeyVisual()
    {
        $this->keyVisual->update([
            'name'          => $this->request->name,
            'link'          => $this->request->link,
            'modified_by'   => auth()->id(),
        ]);

        return $this;
    }

    private function saveBanner()
    {

        (new KeyVisualBannerProcess($this->keyVisual, $this->request->file('banner')))->upload();

        return $this;
    }


    private function saveMobileBanner()
    {

        (new KeyVisualMobileBannerProcess($this->keyVisual, $this->request->file('mobile_banner')))->upload();

        return $this;
    }


}
