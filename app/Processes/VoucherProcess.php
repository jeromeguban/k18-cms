<?php

namespace App\Processes;

use App\Models\CustomerVoucher;
use App\Models\Voucher;
use App\Models\VoucherSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VoucherProcess
{
    protected $request, $voucher, $voucher_settings, $customer_vouchers;

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
     * Retrive Voucher.
     *
     * @return
     */

    public function find($id)
    {

        $this->voucher = Voucher::findOrFail($id);

        return $this;
    }

    public function voucher()
    {
        return $this->voucher;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->request->validate([
                'name' => 'required',
                'code' => 'required|unique:vouchers',
                'type' => 'required',
                'voucher_type' => 'required',
                'value' =>
                [
                    'required',
                    Rule::requiredIf($this->request->get('type') == 'Percentage'),
                    Rule::requiredIf($this->request->get('type') == 'Amount'),
                ],
                'limit' => 'required',
                'limit_per_user' => 'required',
                // 'total_usage'     => 'required',
                'category' => 'required',
                'expiration_date' => 'required',
                'voucher_settings' => 'required',
                'customer_id' => 'nullable',
                'minimum_purchase_requirements' => 'required',
            ]);

            $this->createVoucher()
                ->createVoucherSetting()
                ->refreshCustomerVoucher();

            if ($this->request->customer_id) {
                $this->createCustomerVoucher();
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

        $this->request->validate([
            'code' => [
                'required',
                Rule::unique('vouchers')->ignore($this->voucher->id),
            ],
            'value' =>
            [
                'required',
                Rule::requiredIf($this->request->get('type') == 'Percentage'),
                Rule::requiredIf($this->request->get('type') == 'Amount'),
            ],
            'voucher_settings' => 'required',
            'customer_id' => 'nullable',
            'minimum_purchase_requirements' => 'required',
        ]);

        DB::transaction(function () {

            $this->updateVoucher();
            $this->refreshVoucherSettings();
            $this->createVoucherSetting();
            $this->refreshCustomerVoucher();

            if ($this->request->customer_id) {
                $this->createCustomerVoucher();
            }

        });
    }

    public function createVoucher()
    {
        if($this->request->auto_apply == 1){
            $active_auto_apply_vouchers = Voucher::where('auto_apply', 1)
                                ->update([
                                    'auto_apply'    => 0
                                ]);
        }
            
        
        $this->voucher = Voucher::create([
            'term_id' => $this->request->term_id,
            'name' => $this->request->name,
            'code' => strtoupper($this->request->code),
            'type' => $this->request->type,
            'voucher_type' => $this->request->voucher_type,
            'value' => $this->request->value ?? 0,
            'limit' => $this->request->limit ?? 0,
            'limit_per_user' => $this->request->limit_per_user ?? 0,
            // 'total_usage'   => $this->request->total_usage ?? 0,
            'minimum_purchase_requirements' => $this->request->minimum_purchase_requirements ?? 0,
            'category' => $this->request->category,
            'expiration_date' => Carbon::parse($this->request->expiration_date)->toDateTimeString(),
            'active' => $this->request->active ? 1 : 0,
            'auto_apply' => $this->request->auto_apply ? 1 : 0,
            'applicable_to_discounted' => $this->request->applicable_to_discounted ? 1 : 0,
            'applicable_for_cash_on_delivery' => $this->request->applicable_for_cash_on_delivery ? 1 : 0,
            'created_by' => auth()->id(),
            'modified_by' => auth()->id(),
        ]);

        return $this;
    }

    public function delete()
    {
        DB::transaction(function () {
            $this->destroyVoucher();
        });
    }

    private function updateVoucher()
    {
        if($this->request->auto_apply == 1){
            $active_auto_apply_vouchers = Voucher::where('auto_apply', 1)
                                ->update([
                                    'auto_apply'    => 0
                                ]);
        }
        $this->voucher->update([
            'term_id' => $this->request->term_id,
            'name' => $this->request->name,
            'code' => strtoupper($this->request->code),
            'type' => $this->request->type,
            'voucher_type' => $this->request->voucher_type,
            'value' => $this->request->value ?? 0,
            'limit' => $this->request->limit ?? 0,
            'limit_per_user' => $this->request->limit_per_user ?? 0,
            // 'total_usage'   => $this->request->total_usage ?? 0,
            'minimum_purchase_requirements' => $this->request->minimum_purchase_requirements ?? 0,
            'category' => $this->request->category,
            'expiration_date' => Carbon::parse($this->request->expiration_date)->toDateTimeString(),
            'active' => $this->request->active ? 1 : 0,
            'auto_apply' => $this->request->auto_apply ? 1 : 0,
            'applicable_to_discounted' => $this->request->applicable_to_discounted ? 1 : 0,
            'applicable_for_cash_on_delivery' => $this->request->applicable_for_cash_on_delivery ? 1 : 0,
            'modified_by' => auth()->id(),
        ]);

        return $this;
    }

    private function refreshVoucherSettings()
    {
        VoucherSetting::where('voucher_id', $this->voucher->id)->delete();
    }

    private function createVoucherSetting()
    {

        foreach ($this->request->voucher_settings as $index => $setting) {

            if ($setting['active']) {
                VoucherSetting::updateOrCreate([
                    'voucher_id' => $this->voucher->id,
                    'restriction_name' => $setting['restriction_name'],

                ], [
                    'restriction_value' => json_encode($setting['restriction_value']),
                    'active' => $setting['active'] ? 1 : 0,
                    'created_by' => auth()->id(),
                    'modified_by' => auth()->id(),
                ]);
            }

        };

        return $this;
    }

    private function refreshCustomerVoucher()
    {

        $customer_voucher = CustomerVoucher::where('voucher_id', $this->voucher->id)->first();

        if ($customer_voucher) {
            $customer_voucher->delete();
        }

    }

    private function createCustomerVoucher()
    {

        if ($this->request->customer_id) {
            CustomerVoucher::updateOrCreate([
                'voucher_id' => $this->voucher->id,
                'customer_id' => $this->request->customer_id,

            ], [
                'created_by' => auth()->id(),
                'modified_by' => auth()->id(),
            ]);
        }

        return $this;
    }

    private function destroyVoucher()
    {
        $this->refreshVoucherSettings();
        $this->voucher->delete();
    }
}
