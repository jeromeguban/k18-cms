<?php

namespace App\Jobs\Seller;

use App\Helpers\GuzzleRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSellerPaymentGatewayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order, $bypass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $bypass = false)
    {
        $this->order = $order;
        $this->bypass = $bypass;
        $this->onQueue('payment-gateway');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $form = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'json' => $this->form(),
        ];

        $request = new GuzzleRequest($form);
        $response = $request->post(env('SELLER_PLATFORM_TOKEN_URL') . '/api/orders/' . $this->order->id . '/payments');

        if ($response->getStatusCode() != 200) {

            if ($response->getStatusCode() == 403) {
                return;
            }

            abort($response->getStatusCode(), json_encode($request->data()));

        }

    }

    private function form()
    {

        $payment_transaction = PaymentTransaction::selectedFields()
            ->where('reference_code', $this->order->reference_code)
            ->whereNull('deleted_at')
            ->first()
            ->toArray();

        $payment = Payment::selectedFields()
            ->where('reference_code', $this->order->reference_code)
            ->whereNull('deleted_at')
            ->first()
            ->toArray();

        $data = [
            'payment_id' => $this->order->payment_id,
            'payment' => $payment,
            'payment_transaction' => $payment_transaction,
            'reference_code' => $this->order->reference_code,
            'signature' => sha1($this->order->reference_code . '{' . env('API_SIGNATURE_KEY') . '}'),
        ];

        if ($this->bypass) {
            $data['bypass'] = 1;
        }

        return $data;
    }

}
