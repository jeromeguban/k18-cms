<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black"><strong>Event Name</strong></td>
	    	<td style="border: 1px solid black"><strong>Starting Time</strong></td>
	    	<td style="border: 1px solid black"><strong>Item Name</strong></td>
	    	<td style="border: 1px solid black"><strong>Item Price</strong></td>
	    	<td style="border: 1px solid black"><strong>Ordered Quantity</strong></td>
	    	<td style="border: 1px solid black"><strong>Ordered Total</strong></td>
	    	<td style="border: 1px solid black"><strong>Customer</strong></td>
	    	<td style="border: 1px solid black"><strong>Customer Email</strong></td>
	    	<td style="border: 1px solid black"><strong>Customer Mobile No.</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr>
				<td>{{ $item->event_name}} </td>
				<td>{{ $item->starting_time}} </td>
				<td>{{ $item->item_name}} </td>
				<td>{{ $item->price}} </td>
				<td>{{ $item->ordered_quantity}} </td>
				<td>{{ $item->total}} </td>
                <td>{{ $item->customer_firstname }} {{ $item->customer_lastname  }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->mobile_no }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
