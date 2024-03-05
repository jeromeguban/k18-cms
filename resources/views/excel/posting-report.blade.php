<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black" width="20"><strong>Auction</strong></td>
	    	<td style="border: 1px solid black" width="20"><strong>Lot Number</strong></td>
	    	<td style="border: 1px solid black" width="20"><strong>Bid Amount</strong></td>
			<td style="border: 1px solid black" width="20"><strong>Name </strong></td>
			<td style="border: 1px solid black" width="20"><strong>Contact No. </strong></td>
			<td style="border: 1px solid black" width="20"><strong>Email </strong></td>
	    </tr>
	</thead>
	<tbody>				
		@foreach($items as $item)
			<tr>
				<td> {{ $item->auction_number}} </td>
				<td> {{ $item->lot_number}} </td>
				<td> {{ $item->max_bid}} </td>
				<td>{{ $item->customer_firstname }} {{ $item->customer_lastname }}</td>
				<td>{{ $item->mobile_no }}</td>
				<td>{{ $item->email }}</td>
			</tr>	
		@endforeach
	</tbody> 
</table>
