<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black"><strong>Customer Name</strong></td>
	    	<td style="border: 1px solid black"><strong>Mobile No.</strong></td>
	    	<td style="border: 1px solid black"><strong>Email</strong></td>
	    	<td style="border: 1px solid black"><strong>Item</strong></td>
	    	<td style="border: 1px solid black"><strong>Quantity</strong></td>
	    	<td style="border: 1px solid black"><strong>Inventory</strong></td>
	    	<td style="border: 1px solid black"><strong>Total Price</strong></td>
	    	<td style="border: 1px solid black"><strong>Cart Expirt Date</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr>
				<td>{{ $item->customer_firstname }} - {{ $item->customer_lastname }}</td>
				<td>{{ $item->mobile_no }}</td>
				<td>{{ $item->email }}</td>
				<td>{{ $item->name }}</td>
				<td>{{ $item->quantity }}</td>
				<td>{{ $item->posting_quantity }}</td>
				<td>{{ $item->total_price }}</td>
				<td>{{ $item->expires_at }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
