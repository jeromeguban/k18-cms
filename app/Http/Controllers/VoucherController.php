<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherSetting;
use App\Processes\VoucherProcess;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    protected $voucher;

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
        $this->authorize('list', Voucher::class);

        $query = Voucher::selectedFields()
            ->leftJoinTerms()
            ->withRelations()
            ->searchable()
            ->sortable();

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, VoucherProcess $process)
    {
        $this->authorize('create', Voucher::class);
        $this->authorize('create', VoucherSetting::class);

        $process->create();

        activity()
            ->performedOn($process->voucher())
            ->withProperties($process->voucher())
            ->log('Ads has been created');

        return [
            'success' => 1,
            'data' => $process->voucher(),
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        $this->authorize('view', $voucher);

        return Voucher::selectRaw("
                        vouchers.*,
                        (CASE
                            WHEN vouchers.active = 0 THEN 'No'
                            WHEN vouchers.active = 1 THEN 'YES'
                        END) AS 'active'
                    ")
            ->where('id', $voucher->id)
            ->withRelations()
            ->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher, VoucherProcess $process)
    {
        $this->authorize('update', $voucher);

        $process->find($voucher->id)
            ->update();

        activity()
            ->performedOn($process->voucher())
            ->withProperties($process->voucher())
            ->log('Ads has been updated');

        return [
            'data' => $process->voucher(),
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
    public function setActive(Request $request, Voucher $voucher)
    {
        $this->authorize('update', $voucher);
        $voucher->update([
            'active' => $request->active == 0 ? 1 : 0,
            'applicable_to_discounted' => $request->applicable_to_discounted == 0 ? 1 : 0,
        ]);

        activity()
            ->performedOn($voucher)
            ->withProperties($voucher)
            ->log('Voucher has been updated');

        return [
            'success' => 1,
            'data' => $voucher,
        ];
    }

    /**
     * Delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher, VoucherProcess $process)
    {
        $this->authorize('delete', $voucher);

        $process->find($voucher->id)
            ->delete();

        activity()
            ->performedOn($process->voucher())
            ->withProperties($process->voucher())
            ->log('Voucher has been deleted');

        return ['success' => 1];

    }
}
