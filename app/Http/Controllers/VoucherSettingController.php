<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\VoucherSetting;

class VoucherSettingController extends Controller
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
     * Display the specified resource.
     *
     * @param  Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function index(Voucher $voucher, VoucherSetting  $voucher_setting )
    {
        $this->authorize('list', $voucher_setting);

        $query = VoucherSetting::where('voucher_settings.voucher_id', $voucher->id)
                    ->searchable()
                    ->sortable();
 
        return $this->response($query);
    }

}
