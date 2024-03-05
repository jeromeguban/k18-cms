<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use App\Models\Searchable\PaymentType as SearchablePaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
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
    public function index()
    {

        $this->authorize('list', PaymentType::class);

        $query = PaymentType::selectedFields()
            ->withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', PaymentType::class);

        $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'description' => 'required',
            'redirect_to' => 'required',
        ], [], [
            'name' => 'Name',
            'icon' => 'Icon',
            'description' => 'Description',
            'redirect_to' => 'Redirect To',
        ]);

        $payment_type = PaymentType::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'redirect_to' => $request->redirect_to,
            'created_by' => auth()->id(),
            'modified_by' => auth()->id(),
        ]);

        SearchablePaymentType::where('id', $payment_type->id)->searchable();

        activity()
            ->performedOn($payment_type)
            ->withProperties($payment_type)
            ->log('Payment Type has been created');

        return [
            'success' => 1,
            'data' => $payment_type,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentType  $payment_type
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentType $payment_type)
    {
        $this->authorize('view', $payment_type);

        return PaymentType::where('id', $payment_type->id)
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
    public function update(Request $request, PaymentType $payment_type)
    {
        $this->authorize('update', $payment_type);

        $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'description' => 'required',
            'redirect_to' => 'required',
        ], [], [
            'name' => 'Name',
            'icon' => 'Icon',
            'description' => 'Description',
            'redirect_to' => 'Redirect To',
        ]);

        $payment_type->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'redirect_to' => $request->redirect_to,
            'modified_by' => auth()->id(),
        ]);

        SearchablePaymentType::where('id', $payment_type->id)->searchable();

        activity()
            ->performedOn($payment_type)
            ->withProperties($payment_type)
            ->log('Payment Type has been updated');

        return [
            'success' => 1,
            'data' => $payment_type,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentType $payment_type)
    {

        $this->authorize('delete', $payment_type);

        SearchablePaymentType::where('id', $payment_type->id)->unsearchable();

        $payment_type->delete();

        activity()
            ->performedOn($payment_type)
            ->withProperties($payment_type)
            ->log('Payment Type has been deleted');

        return [
            'success' => 1,
        ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActive(Request $request, PaymentType $payment_type)
    {
        $this->authorize('update', $payment_type);

        $payment_type->update([
            'active' => $request->active == 0 ? 1 : 0,
            'modified_by' => auth()->id(),
        ]);

        SearchablePaymentType::where('id', $payment_type->id)->searchable();

        activity()
            ->performedOn($payment_type)
            ->withProperties($payment_type)
            ->log('Payment Type has been updated');

        return [
            'success' => 1,
            'data' => $payment_type,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setStatus(Request $request, PaymentType $payment_type)
    {
        $this->authorize('update', $payment_type);

        $payment_type->update([
            'status' => $request->status == 0 ? 1 : 0,
            'modified_by' => auth()->id(),
        ]);

        SearchablePaymentType::where('id', $payment_type->id)->searchable();

        activity()
            ->performedOn($payment_type)
            ->withProperties($payment_type)
            ->log('Payment Type has been updated');

        return [
            'success' => 1,
            'data' => $payment_type,
        ];
    }

}
