<?php

namespace App\Console\Commands;

use App\Models\BidderDeposit;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Processes\OrderPaymentProcess;
use App\Processes\PaymayaCallbackProcess;
use App\Processes\PaymentGatewayCallbackProcess;
use Illuminate\Console\Command;

class ValidatePendingPaymentTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:pending-payment-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate Pending Online Payment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $payment_transactions = PaymentTransaction::where('transaction_status', 'Pending')
            ->whereBetween('created_at', ['2023-01-25 00:00:00', now()->toDateTimeString()])
            ->whereNull('deleted_at')
            ->get();

        foreach ($payment_transactions as $payment_transaction) {

            try {

                $validate_reference_code = explode('-', $payment_transaction->reference_code);

                $type = count($validate_reference_code) == 1 ? 'order' : (count($validate_reference_code) == 2 ? 'bidder_deposit' : null);

                $reference = null;

                switch ($type) {
                    case 'order':
                        $reference = Order::selectedFields()
                            ->where('orders.reference_code', $payment_transaction->reference_code)
                            ->orderBy('orders.created_at', 'DESC')
                            ->first();
                        break;

                    case 'bidder_deposit':
                        $reference = BidderDeposit::selectedFields()
                            ->where('bidder_deposits.reference_code', $payment_transaction->reference_code)
                            ->first();
                        break;
                }

                $payment_type = PaymentType::where('id', $reference->payment_type_id)
                    ->where('status', 1)
                    ->first();

                if (!$payment_type) {
                    return $this->comment("No Payment Type found on Order.");
                }

                try {
                    $payment_details = (new OrderPaymentProcess(['payment_type_id' => $reference->payment_type_id]))
                        ->setReferenceCode($payment_transaction->reference_code)
                        ->getPaymentGatewayDetails();
                } catch (\Exception $e) {
                    $payment_details = null;
                }

                if ($payment_details) {

                    switch ($payment_type->type) {
                        case 'Bux':
                            $request = [
                                'req_id' => $payment_transaction->reference_code,
                                'status' => strtolower($payment_details['status']),
                                'extras' => [
                                    'fee' => $payment_details['fee'],
                                ],
                            ];

                            $process = (new PaymentGatewayCallbackProcess($request))
                                ->handle();
                            break;
                        case 'Paymaya':
                            $process = (new PaymayaCallbackProcess($payment_details->toArray()))
                                ->handle();
                            break;
                    }
                }
            } catch (\Exception $e) {
            }

            $this->comment("Payment Transaction Validation Successful!");
        }
    }
}