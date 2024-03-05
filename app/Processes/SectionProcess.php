<?php

namespace App\Processes;

use App\Models\Section;
use App\Processes\SectionBannerProcess;
use App\Processes\SectionMobileBannerProcess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SectionProcess
{
	protected $request, $section;

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
     * Retrive Section.
     *
     * @return 
    */
    public function find($id)
    {
        $this->section = Section::findOrFail($id);
        
        return $this;
    }

    public function section()
    {
        return $this->section;
    }

    /**
     * Execute create process.
     *
     * @return void
    */

    public function create()
    {

    	DB::transaction(function(){

           $this->createSection();
           if($this->request->banner){
                $this->saveBanner()
                ->saveMobileBanner();
            }
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
            $this->updateSection();
        });
        
    }

    public function createSection()
    {
        $parameters = $this->request->section_type == 'item-posting' ? json_decode($this->request->parameters) : null;
    	$this->section = Section::create([
    		'name'			=> $this->request->name,
            'section_type'  => $this->request->section_type,
    		'section_label'	=> $this->request->section_label,
    		'link'			=> $this->request->section_type == 'banner-upload' ? $this->request->link : 'N/A',
            'active'        => $this->request->active == 'true' ? 1:0,
            'parameters'    => $parameters ? json_encode($parameters) : null,
            'type'          => $this->request->type,
    		'created_by'    => auth()->id(), 
            'modified_by'   => auth()->id(),
    	]);

    	return $this;
    }

    public function updateSection()
    {
        

    	$this->section->update([
    		'name'			=> $this->request->name,
    		'section_label'	=> $this->request->section_label,
            'parameters'    => json_encode($this->request->parameters),
    		'link'			=> $this->request->link,
            'type'          => $this->request->type,
            'modified_by'   => auth()->id(),
    	]);

    	return $this;
    }

    public function saveBanner()
    {
        (new SectionBannerProcess($this->section, $this->request->file('banner')))->upload();

        return $this;
    }

    public function saveMobileBanner()
    {
        (new SectionMobileBannerProcess($this->section, $this->request->file('mobile_banner')))->upload();

        return $this;
    }
}