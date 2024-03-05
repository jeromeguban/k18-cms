<?php

namespace App\Processes;

use App\Helpers\BuxHelper;
use App\Helpers\PaymayaHelper;
use App\Models\PaymentType;
use Illuminate\Support\Facades\DB;

class OrderPaymentProcess
{

    protected $request, $payment_type, $helper;

    protected $non_online_payment_channels = [
        'OP'
    ];

    protected $types = [
        'Bux',
        'Paymaya'
    ];

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = $request ? (object) $request : request();
        $this->payment_type = PaymentType::find($this->request->payment_type_id);

        if (in_array($this->payment_type->code, $this->non_online_payment_channels)) {
            $this->helper = new BuxHelper($this->request, $this->payment_type);
            return $this;
        }

        abort_if(!in_array($this->payment_type->type, $this->types), 403, 'Something went wrong. Please contact our customer support.');

        switch ($this->payment_type->type) {
            case 'Bux':
                $this->helper = new BuxHelper($this->request, $this->payment_type);
                break;
            case 'Paymaya':
                $this->helper = new PaymayaHelper($this->request, $this->payment_type);
                break;
            default:
                abort(403, 'Something went wrong. Please contact our customer support.');
        }
    }

    public function paymentType()
    {
        return $this->payment_type;
    }


    /**
     * Execute find process.
     *
     * @return void
     */

    public function setReferenceCode($reference_code)
    {
        $this->helper->setReferenceCode($reference_code);
        return $this;
    }


    /**
     * Retrive Payment Transaction.
     *
     * @return 
     */

    public function paymentTransaction()
    {
        return $this->helper->paymentTransaction();
    }

    /**
     * Retrive Payment Gateway Details.
     *
     * @return 
     */

    public function getPaymentGatewayDetails()
    {
        return $this->helper->getPaymentGatewayDetails();
    }


    /**
     * Set Store Type.
     *
     * @return 
     */

    public function setStoreType($store_type)
    {
        $this->helper->setStoreType($store_type);
        return $this;
    }

    /**
     * Execute transact process.
     *
     * @return void
     */

    public function transact()
    {
        $this->helper->transact();
        return $this;
    }
}
