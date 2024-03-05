<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black" width="20"><strong>Bidder No.</strong></td>
			<td style="border: 1px solid black" width="20"><strong>Name </strong></td>
			<td style="border: 1px solid black" width="20"><strong>Contact No. </strong></td>
			<td style="border: 1px solid black" width="20"><strong>Email </strong></td>
			<td style="border: 1px solid black" width="20"><strong>Date Account Created </strong></td>
	    </tr>
	</thead>
	<tbody>				
		@foreach($bidders as $bidder)
			<tr>
				<td>{{ $bidder->bidder_number }}</td>
				<td>{{ $bidder->customer_firstname }} {{ $bidder->customer_lastname }}</td>
				<td>{{ $bidder->mobile_no }}</td>
				<td>{{ $bidder->email }}</td>
				<td>{{ $bidder->created_at }}</td>
			</tr>	
		@endforeach
	</tbody> 
</table>
