 <table>
     <thead>
         <tr>
             <td colspan="20" style="border: 2px solid black;  text-align: center;  font-size: 15px; ">
                 <center>
                     <h2>
                         Delivery Report
                     </h2>
                 </center>
             </td>
         </tr>
     </thead>
 </table>
 <table>
     <thead>
         <tr style="border: 1px thick #000">
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Order</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Date</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Customer</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Address</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Total Order Amount</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Shipping Fee</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Shipmates Fee</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Total Amount</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Delivery Type</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Receivables Amount</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Payables Amount</strong></td>
         </tr>
     </thead>
     <tbody>
        @php
            $totalOrderAmount = 0;
            $totalShippingFee = 0;
            $totalShipmatesFee = 0;
            $totalAmount = 0;
            $totalReceivableAmount = 0;
            $totalPayableAmount = 0;
        @endphp
         @foreach($items as $item)
         <tr>
             <td colspan="2" style="text-align: left;">{{ $item->id }}</td>
             <td colspan="2" style="text-align: left;">{{ $item->order_date }}</td>
             <td colspan="2" style="text-align: left;">{{ $item->customer_firstname }}, {{ $item->customer_lastname }}</td>
             <td colspan="2" style="text-align: left;">{{ $item->address }}</td>
             <td colspan="2" style="text-align: left;">{{ $item->total_order_amount }}</td>
             <td colspan="2" style="text-align: left;">{{ $item->shipping_fee }}</td>
             @php
                $courierDetails = json_decode($item->courier_details);
            @endphp
             <td colspan="2" style="text-align: left;">{{ $courierDetails->fees->shipmates_fee  }}</td>
             <td colspan="2" style="text-align: left;">{{ $item->total_order_amount +  $item->shipping_fee }}</td>
             <td colspan="2" style="text-align: left;">{{ isset($courierDetails->cash_on_delivery) && $courierDetails->cash_on_delivery == true ? 'COD' : 'Regular' }}</td>
             <td colspan="2" style="text-align: left;">{{ isset($courierDetails->cash_on_delivery) && $courierDetails->cash_on_delivery == true ? $item->total_order_amount  : 0 }}</td>
             <td colspan="2" style="text-align: left;">{{ (!isset($courierDetails->cash_on_delivery) || !$courierDetails->cash_on_delivery) ? ($courierDetails->fees->shipmates_fee + $item->shipping_fee) : 0 }}</td>
             @php
                $totalOrderAmount += $item->total_order_amount;
                $totalShippingFee += $item->shipping_fee;
                $totalShipmatesFee += $courierDetails->fees->shipmates_fee;
                $totalAmount += $item->total_order_amount + $item->shipping_fee;

                $receivable = isset($courierDetails->cash_on_delivery) && $courierDetails->cash_on_delivery == true ? $item->total_order_amount : 0;
                $totalReceivableAmount += $receivable;

                $payable = (!isset($courierDetails->cash_on_delivery) || !$courierDetails->cash_on_delivery) ? ($courierDetails->fees->shipmates_fee + $item->shipping_fee) : 0;
                $totalPayableAmount += $payable;
            @endphp
        @endforeach
         </tr>
         <tr>
            <td colspan="2" style="text-align: left;"><strong>Total:</strong></td>
            <td colspan="2"></td> <!-- Empty columns for Date -->
            <td colspan="2"></td> <!-- Empty columns for Customer -->
            <td colspan="2"></td> <!-- Empty columns for Address -->
            <td colspan="2" style="text-align: left;">{{ $totalOrderAmount }}</td>
            <td colspan="2" style="text-align: left;">{{ $totalShippingFee }}</td>
            <td colspan="2" style="text-align: left;">{{ $totalShipmatesFee }}</td>
            <td colspan="2" style="text-align: left;">{{ $totalAmount }}</td>
            <td colspan="2"></td> <!-- Empty columns for Delivery Type -->
            <td colspan="2" style="text-align: left;">{{ $totalReceivableAmount }}</td>
            <td colspan="2" style="text-align: left;">{{ $totalPayableAmount }}</td>
        </tr>
         
     </tbody>
 </table>