<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CareerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (request()->method()) {
            case "POST":
                return $this->createValidation();
            break;
            case "PATCH":
                return $this->updateValidation();
            break;
        }
    }

    public function createValidation()
    {
        return [
            'position_title'            => 'required|max:191',
            'job_description'           => 'required',
            'employment_type'           => 'required',
            'position_level'            => 'required',
            'job_specialization'        => 'nullable',
            'job_role'                  => 'nullable',
            'work_location'             => 'required',
            'monthly_salary'            => 'nullable',
            'monthly_salary_display'    => 'nullable',
            'job_requirements'          => 'nullable',
            'banner'                    => 'nullable',
            'mobile_banner'             => 'nullable',
            'receiver'                  => 'required'
        ];
    }

    public function updateValidation()
    {
        return [
            'position_title'            => 'required|max:191',
            'job_description'           => 'required',
            'employment_type'           => 'required',
            'position_level'            => 'required',
            'job_specialization'        => 'nullable',
            'job_role'                  => 'nullable',
            'work_location'             => 'required',
            'monthly_salary'            => 'nullable',
            'monthly_salary_display'    => 'nullable',
            'job_requirements'          => 'nullable',
            'banner'                    => 'nullable',
            'mobile_banner'             => 'nullable',
            'receiver'                  => 'required|array'
        ];
    }

    public function attributes()
    {
        return [
            'position_title'            => 'Position Title',
            'job_description'           => 'Job Description',
            'employment_type'           => 'Employment Type',
            'position_level'            => 'Position Level',
            'job_specialization'        => 'Job Specialization',
            'job_role'                  => 'Job Role',
            'work_location'             => 'Work Location',
            'monthly_salary'            => 'Monthly Salary',
            'job_requirements'          => 'Job Requirements',
            'banner'                    => 'Banner',
            'mobile_banner'             => 'Mobile Banner',
        ];
    }
}
