<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black"><strong>Order Number</strong></td>
	    	<td style="border: 1px solid black"><strong>Reference Code</strong></td>
	    	<td style="border: 1px solid black"><strong>Customer</strong></td>
	    	<td style="border: 1px solid black"><strong>Date Ordered</strong></td>
	    	<td style="border: 1px solid black"><strong>Cancellation Date</strong></td>
	    	<td style="border: 1px solid black"><strong>Cancellation Reason</strong></td>
	    	<td style="border: 1px solid black"><strong>Checkout Method</strong></td>
	    	<td style="border: 1px solid black"><strong>Payment Status</strong></td>
	    	<td style="border: 1px solid black"><strong>Order Status</strong></td>
	    	<td style="border: 1px solid black"><strong>Released Type</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr>
				<td>{{ $item->id }}</td>
				<td>{{ $item->reference_code }}</td>
				<td>{{ $item->customer_firstname }}, {{ $item->customer_lastname }}</td>
                <td>{{ $item->order_date }}</td>
                <td>{{ $item->cancelled_date }}</td>
                <td>{{ $item->cancellation_reason }}</td>
                <td>{{ $item->checkout_method }}</td>
                <td>{{ $item->payment_status }}</td>
                <td>{{ $item->order_status }}</td>
                <td>{{ $item->release_type }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
