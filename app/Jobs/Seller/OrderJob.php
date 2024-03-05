<?php

namespace App\Jobs\Seller;

use App\Models\Order;
use App\Helpers\GuzzleRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
        $response = $request->post(env('SELLER_PLATFORM_TOKEN_URL') . '/api/order-waybill-callbacks');
        if ($response->getStatusCode() != 200) {

            if ($response->getStatusCode() == 403) {
                return;
            }

            abort($response->getStatusCode(), json_encode($request->data()));

        }
    }

    private function form()
    {

        $order = Order::selectedFields()
            ->joinStore()
            ->where('orders.id', $this->order->id)
            ->first()
            ->toArray();
        

        $data = [
            'order' => $order,
            'reference_code'    => $this->order->reference_code,
            'signature' => sha1($this->order->reference_code . '{' . env('API_SIGNATURE_KEY') . '}'),
        ];

        return $data;
    }
}
