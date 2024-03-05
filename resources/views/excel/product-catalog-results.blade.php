<table>
	<thead>
		<tr>
			<td colspan="8" height="30" style="text-align: center;">
				<strong style="text-align: center;">
					Product Catalogs - {{ $event->event_name }}
				</strong>
			</td>
		</tr>
	</thead>
</table>
<table>
	<thead>
		<tr style="border: 1px thick #000">
			<td style="border: 1px solid black"><strong> Product Name </strong></td>
			<td style="border: 1px solid black"><strong> Description </strong></td>
			<td style="border: 1px solid black"><strong> Unit Price </strong></td>
			<td style="border: 1px solid black"><strong> Suggested Retail Price </strong></td>
		</tr>
	</thead>
	<tbody>
		@foreach($postings as $posting)
        <tr>
            <td width="30">{{ $posting->name }}</td>
            <td width="30">{!! $posting->description !!}</td>
            <td width="30">{{ $posting->unit_price }}</td>
            <td width="30">{{ $posting->suggested_retail_price }}</td>
        </tr>
		@endforeach
	</tbody>
</table>
