<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\CareerApplicant;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;

class CareerApplicantController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware(['auth']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Career $career, CareerApplicant $career_applicant)
    {
        $this->authorize('list', CareerApplicant::class);

        $query = CareerApplicant::selectedFields()
                        ->sortable()
                        ->searchable();

        if($career->career_id)
            $query->where('career_id', $career->career_id);

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($career_applicant) {
            $career_applicant =   (new ModelDecrypter)->decryptModel($career_applicant);
        });

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CareerApplicant $career_applicant)
    {
        $this->authorize('view', $career_applicant);

        return CareerApplicant::where('career_applicant_id', $career_applicant->career_applicant_id)
            ->withRelations()
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
