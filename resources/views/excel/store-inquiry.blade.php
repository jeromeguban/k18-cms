<table>
    <thead>
	    <tr style="border: 1px thick #000">
	    	<td style="border: 1px solid black"><strong>Name</strong></td>
	    	<td style="border: 1px solid black"><strong>Mobile no.</strong></td>
	    	<td style="border: 1px solid black"><strong>Email</strong></td>
	    	<td style="border: 1px solid black"><strong>Message</strong></td>
	    	<td style="border: 1px solid black"><strong>Status</strong></td>
			<td style="border: 1px solid black"><strong>Date of Inquiry</strong></td>
			<td style="border: 1px solid black"><strong>Update Time</strong></td>
			<td style="border: 1px solid black"><strong>Response Time</strong></td>
	    </tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr>
				<td>{{ $item->full_name }}</td>
				<td>{{ $item->mobile_no }}</td>
				<td>{{ $item->email }}</td>
				<td>{{ $item->message }}</td>
				<td>{{ $item->status }}</td>
				<td>{{ $item->created_at }}</td>
				<td>{{ $item->status != 'Open' ? $item->updated_at : '' }}</td>
				<td>
					@if($item->status != 'Open')
						@php
							$responseTime = \Carbon\Carbon::parse($item->created_at)->diff(\Carbon\Carbon::parse($item->updated_at));
						@endphp
						{{ $responseTime->days > 0 ? $responseTime->days.' days ' : '' }}
						{{ $responseTime->h > 0 ? $responseTime->h.' hours ' : '' }}
						{{ $responseTime->i > 0 ? $responseTime->i.' minutes ' : '' }}
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
