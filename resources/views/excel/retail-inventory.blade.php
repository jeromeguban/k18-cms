<table>
    <thead>
	    <tr style="border: 1px thick #000">
            <td style="border: 1px solid black"><strong>Company Code</strong></td>
	    	<td style="border: 1px solid black"><strong>Store.</strong></td>
	    	<td style="border: 1px solid black"><strong>Name	</strong></td>
	    	<td style="border: 1px solid black"><strong>Sku</strong></td>
	    	<td style="border: 1px solid black"><strong>Quantity</strong></td>
	    	<td style="border: 1px solid black"><strong>Unit Price</strong></td>
	    	<td style="border: 1px solid black"><strong>Srp</strong></td>
	    	<td style="border: 1px solid black"><strong>Date Published</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($retails as $retail)
			<tr>
                <td>{{ $retail->store_company_code }}</td>
				<td>{{ $retail->store_name }}</td>
				<td>{{ $retail->name }}</td>
                <td>{{ $retail->sku }}</td>
                <td>{{ $retail->quantity }}</td>
                <td>{{ $retail->unit_price }}</td>
                <td>{{ $retail->suggested_retail_price }}</td>
                <td>{{ $retail->published_date }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
