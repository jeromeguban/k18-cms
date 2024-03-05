<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black"><strong>Lot No.</strong></td>
	    	<td style="border: 1px solid black"><strong>Description</strong></td>
	    	<td style="border: 1px solid black"><strong>Starting Time</strong></td>
	    	<td style="border: 1px solid black"><strong>Ending Time</strong></td>
	    	<td style="border: 1px solid black"><strong>Bidder Firstname</strong></td>
	    	<td style="border: 1px solid black"><strong>Bidder Lastname</strong></td>
	    	<td style="border: 1px solid black"><strong>Bid Amount</strong></td>
	    	<td style="border: 1px solid black"><strong>Bidded On</strong></td>
	    	<td style="border: 1px solid black"><strong>Max Bid Indicator</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr>
				<td>{{ $item->lot_number }}</td>
				<td>{{ $item->description }}</td>
				<td>{{ $item->starting_time }}</td>
				<td>{{ $item->ending_time }}</td>
				<td>{{ $item->customer_firstname  }}</td>
       	 		<td>{{ $item->customer_lastname  }}</td>
				<td>{{ $item->bid_amount }}</td>
				<td>{{ $item->created_at }}</td>
				<td>{{ $item->max_bid_indicator }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
