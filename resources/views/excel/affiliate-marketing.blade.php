<table>
    <thead>
	    <tr style="border: 1px thick #000">
            <td style="border: 1px solid black"><strong>MARKETER</strong></td>
	    	<td style="border: 1px solid black"><strong>REFERRAL CODE.</strong></td>
	    	<td style="border: 1px solid black"><strong>NAME	</strong></td>
	    	<td style="border: 1px solid black"><strong>DESCRIPTION</strong></td>
	    	<td style="border: 1px solid black"><strong>QUANTITY</strong></td>
	    	<td style="border: 1px solid black"><strong>PRICE</strong></td>
	    	<td style="border: 1px solid black"><strong>TOTAL PRICE</strong></td>
	    	<td style="border: 1px solid black"><strong>COMMISSION PERCENTAGE</strong></td>
            <td style="border: 1px solid black"><strong>COMMISSION</strong></td>
	    	<td style="border: 1px solid black"><strong>STATUS</strong></td>
	    	<td style="border: 1px solid black"><strong>ORDERED DATE</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($affiliates as $affiliate)
			<tr>
                <td>{{ $affiliate->marketer }}</td>
				<td>{{ $affiliate->referral_code }}</td>
				<td>{{ $affiliate->name }}</td>
                <td>{{ $affiliate->description }}</td>
                <td>{{ $affiliate->quantity }}</td>
                <td>{{ $affiliate->price }}</td>
                <td>{{ $affiliate->total_price }}</td>
                <td>{{ $affiliate->commission }}</td>
                <td>{{ $affiliate->commission_price }}</td>
                <td>{{ $affiliate->status }}</td>
                <td>{{ $affiliate->created_at }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
