<?php 
namespace App\Traits;

use App\Helpers\CacheHelper;
use App\Models\KeyVisual;
use App\Processes\KeyVisualBannerProcess;

trait KeyVisualTrait
{
	private function createKeyVisual()
    {
    	$this->keyVisual = KeyVisual::create([
    		'link'            => $this->request->link ?? null,
            'name'            => $this->request->name ?? null,
            
    	]);

    	return $this;
    }

    private function saveBanner() 
    {
        
        
        (new KeyVisualBannerProcess($this->keyVisual, $this->request->file('banner')))->upload();
        
        return $this;
    }

}