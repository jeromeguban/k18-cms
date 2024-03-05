<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payable</title>
    <style>
        @page {
            margin-top: -0.5cm;
            margin-bottom: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
            border: 1px solid blue;
            height: 100%;
        }

        thead tr {

            padding-top: 678px;

        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 11px;
        }

        .header {
            margin-top: 1cm;
            text-align: center;
        }

        .payable-details {
            padding-left: 30px;
            padding-right: 30px;
        }

        .cancelled-lots {
            margin-top: 10px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .cancelled-lots th,
        .payable-items th {
            border-bottom: 1px solid black;
        }

        .payable-items {
            margin-top: 0px;
            padding-left: 30px;
            padding-right: 30px;
            margin-bottom: 50px;
        }

        .description {
            text-transform: uppercase;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .total {
            width: 30%;
            max-width: 35%;
            margin: auto;
            margin-top: 1cm;
        }
    </style>
</head>

<body>
    <div class="main-header">
        <table>
            <thead>
                <tr>
                    <td colspan="16" height="15" style="text-align: center;">
                        <strong>
                            {{$payables->store_company_code}} - {{$payables->company_name}}
                        </strong>
                    </td>
                </tr>
            </thead>
        </table>
        <div class="payable-details">
            <table>
                <tr>
                    <td colspan="16">Company Code: {{$payables->store_company_code}}</td>
                </tr>
                <tr>
                    <td colspan="16">Company Name: {{$payables->company_name}}</td>
                </tr>
                <tr>
                    <td colspan="16">Bank: {{$payables->bank_name}}</td>
                </tr>
                <tr>
                    <td colspan="16">Check: {{$payables->account_number}}</td>
                </tr>
                <tr>
                    <td colspan="16">Payee Name: {{$payables->check_name}}</td>
                </tr>
                <tr>
                    <td colspan="16">Check Date: {{$payables->check_date}}</td>
                </tr>
                <tr>
                    <td colspan="16">Period: {{$payables->date_from}} - {{$payables->date_to}}</td>
                </tr>
            </table>
        </div>

        <div class="payable-items">
            <h4>Costs</h4>
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="border: 1px solid black" width="100%" colspan="4">Code</th>
                        <th style="border: 1px solid black" width="100%" colspan="4">Company</th>
                        <th style="border: 1px solid black" width="100%" colspan="4">Amount </th>
                        <th style="border: 1px solid black" width="100%" colspan="4">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($costs as $cost)
                    <tr>
                        <td colspan="4">{{ $cost->store_company_code }}</td>
                        <td colspan="4">{{ $cost->company_name}}</td>
                        <td colspan="4">{{number_format($cost->amount, 2)}}</td>
                        <td colspan="4">{{$cost->remarks}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <div class="payable-items">
            <h4>PAYABLE PER STORES</h4>
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="border: 1px solid black" width="100%" colspan="2">Store</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Store Code</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Discount </th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Sub Total</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Commission</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Costs</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Payable Amount</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Sold Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payable_stores as $payable_store)
                    <tr>
                        <td colspan="2">{{ $payable_store->store_name }}</td>
                        <td colspan="2">{{ $payable_store->store_code}}</td>
                        <td colspan="2">{{number_format($payable_store->discount_total_amount,2)}}</td>
                        <td colspan="2">{{number_format($payable_store->sub_total_amount, 2)}}</td>
                        <td colspan="2">{{number_format($payable_store->total_commission, 2)}}</td>
                        <td colspan="2">{{number_format($payable_store->total_costs, 2)}}</td>
                        <td colspan="2">{{number_format($payable_store->total_payable_amount, 2)}}</td>
                        <td colspan="2">{{number_format($payable_store->total_sold_amount, 2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tbody>
                    <tr>
                        <td style="border: 1px solid black" colspan="4"> TOTAL</td>
                        <td style="border: 1px solid black" colspan="2">{{number_format($payables->discount_total_amount,2)}}</td>
                        <td style="border: 1px solid black" colspan="2">{{$payables->sub_total_amount}}</td>
                        <td style="border: 1px solid black" colspan="2">{{number_format($payables->total_commission, 2)}}</td>
                        <td style="border: 1px solid black" colspan="2">{{number_format($payables->total_costs, 2)}}</td>
                        <td style="border: 1px solid black" colspan="2">{{number_format($payables->total_payable_amount, 2)}}</td>
                        <td style="border: 1px solid black" colspan="2">{{$payables->total_sold_amount}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class="payable-items">
            <h4>PAYABLE ITEMS</h4>
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="border: 1px solid black" width="100%" colspan="2">Store</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Store Code</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Item Name </th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Sub Total</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Discount Price</th>
                        <th style="border: 1px solid black" width="100%" colspan="2">Total Sold Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payable_items as $payable_item)
                    <tr>
                        <td colspan="2">{{ $payable_item->store_name }}</td>
                        <td colspan="2">{{ $payable_item->code}}</td>
                        <td colspan="2">{{$payable_item->item_name}}</td>
                        <td colspan="2">{{number_format($payable_item->order_sub_total, 2)}}</td>
                        <td colspan="2">{{number_format($payable_item->discount_price, 2)}}</td>
                        <td colspan="2">{{number_format($payable_item->order_sold_amount, 2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</body>

</html>