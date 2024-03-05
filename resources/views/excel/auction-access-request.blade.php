<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black"><strong>Customer</strong></td>
	    	<td style="border: 1px solid black"><strong>Mobile No.</strong></td>
	    	<td style="border: 1px solid black"><strong>Email</strong></td>
	    	<td style="border: 1px solid black"><strong>Auction Number</strong></td>
	    	<td style="border: 1px solid black"><strong>Status</strong></td>
	    	<td style="border: 1px solid black"><strong>Date Evaluated</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr>
                <td>{{ $item->customer_firstname }} {{ $item->customer_lastname  }}</td>
                <td>{{ $item->mobile_no }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->auction_number }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->evaluated_at }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
