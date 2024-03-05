<table>
    <thead>
        <tr>
            <td colspan="32" height="30" style="text-align: center;">
                <strong>
                    Customer List &nbsp;{{ $from }} &nbsp; - &nbsp; {{ $to }}
                </strong>
            </td>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr style="border: 1px thick #000">
            <td colspan="3" style="border: 1px solid black"><strong>Customer First Name</strong></td>
            <td colspan="3" style="border: 1px solid black"><strong>Customer Last Name</strong></td>
            <td colspan="3" style="border: 1px solid black"><strong>Contact Number</strong></td>
            <td colspan="3" style="border: 1px solid black"><strong>Email </strong></td>
            <!-- <td colspan="3" style="border: 1px solid black"><strong>Address </strong></td> -->
            <td colspan="3" style="border: 1px solid black"><strong>Date Created </strong></td>
            <td colspan="20" style="border: 1px solid black"><strong>Address </strong></td>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td colspan="3">{{ $customer->customer_firstname }} </td>
            <td colspan="3">{{ $customer->customer_lastname }} </td>
            <td colspan="3">{{ $customer->mobile_no}} </td>
            <td colspan="3">{{ $customer->email}} </td>
            <!-- <td colspan="3">{{ $customer->address}} </td> -->
            <td colspan="3">{{ $customer->created_at}} </td>
            <td colspan="20">{{ $customer->country}} , {{ $customer->province}}, {{ $customer->city}}, {{ $customer->barangay}}, {{ $customer->zipcode}}, {{ $customer->additional_information}} </td>
        </tr>
        @endforeach
    </tbody>
</table>