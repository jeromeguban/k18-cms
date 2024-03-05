<?php

namespace App\Processes;

use App\Models\Career;
use Illuminate\Support\Facades\DB;
use App\Processes\CareerBannerProcess;
use App\Processes\CareerMobileBannerProcess;

class CareerProcess
{
	protected $request , $career;

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
     * Execute find process.
     *
     * @return void
     */

    public function find($id)
    {

        $this->career = Career::findOrFail($id);

        return $this;

    }

    /**
     * Retrive career.
     *
     * @return
     */

    public function career()
    {

        return $this->career;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->createCareer();

            if($this->request->banner && $this->request->mobile_banner != null){
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

        DB::transaction(function () {

            $this->updateCareer();

        });

    }

    public function createCareer()
    {
    	$this->career = Career::create([
    		'position_title'			=> $this->request->position_title,
    		'job_description'			=> $this->request->job_description,
    		'employment_type'			=> $this->request->employment_type,
    		'position_level'			=> $this->request->position_level,
    		'job_specialization'		=> $this->request->job_specialization,
    		'job_role'					=> $this->request->job_role,
    		'work_location'				=> str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->work_location)))),
    		'monthly_salary'			=> str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->monthly_salary)))),
    		'monthly_salary_display'	=> $this->request->monthly_salary_display ? 1 : 0 ,
    		'job_requirements'			=> str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->job_requirements)))),
    		'hr_receive'			    => str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->receiver)))),
    		'created_by' 				=> auth()->user()->id,
            'modified_by' 				=> auth()->user()->id,
    	]);

    	return $this;
    }

    public function updateCareer()
    {
    	$this->career->update([
    		'position_title'			=> $this->request->position_title,
    		'job_description'			=> $this->request->job_description,
    		'employment_type'			=> $this->request->employment_type,
    		'position_level'			=> $this->request->position_level,
    		'job_specialization'		=> $this->request->job_specialization,
    		'job_role'					=> $this->request->job_role,
    		'work_location'				=> str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->work_location)))),
    		'monthly_salary'			=> str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->monthly_salary)))),
    		'monthly_salary_display'	=> $this->request->monthly_salary_display ? 1 : 0 ,
    		'job_requirements'			=> str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->job_requirements)))),
    		'hr_receive'			    => str_replace(["\"[", "]\""], ["[", "]"], str_replace(["\"{", "}\""], ["{", "}"], str_replace("\\", "", json_encode($this->request->hr_receive)))),
            'modified_by' 				=> auth()->user()->id,
    	]);

    	return $this;
    }

    private function saveBanner()
    {

        (new CareerBannerProcess($this->career, $this->request->file('banner')))->upload();

        return $this;
    }

    private function saveMobileBanner()
    {

        (new CareerMobileBannerProcess($this->career, $this->request->file('mobile_banner')))->upload();

        return $this;
    }

}
