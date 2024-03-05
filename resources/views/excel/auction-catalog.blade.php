<table>
	<thead>
		<tr>
			<td colspan="18" height="30" style="text-align: center;">
				<strong style="text-align: center;">
					Auction Event - {{ $auction->auction_number }}
				</strong>
			</td>
		</tr>
	</thead>
</table>
<table>
	<thead>
		<tr style="border: 1px strong #000">
			<td style="border: 1px solid black"><strong> Lot Number </strong></td>
            <td style="border: 1px solid black"><strong> Name </strong></td>
			<!-- <td style="border: 1px solid black"><strong> Unit Description </strong></td> -->
            <td style="border: 1px solid black"><strong> Color </strong></td>
            <td style="border: 1px solid black"><strong> Gear type </strong></td>
            <td style="border: 1px solid black"><strong> Plate Number </strong></td>
            <td style="border: 1px solid black"><strong> Mileage </strong></td>
            <td style="border: 1px solid black"><strong> Body Type </strong></td>
            <td style="border: 1px solid black"><strong> Fuel Type </strong></td>
            <td style="border: 1px solid black"><strong> Condition </strong></td>
            <td style="border: 1px solid black"><strong> Body Condition </strong></td>
            <td style="border: 1px solid black"><strong> Battery </strong></td>
            <td style="border: 1px solid black"><strong> Last Registration </strong></td>
            <td style="border: 1px solid black"><strong> Field office </strong></td>
            <td style="border: 1px solid black"><strong> Interior </strong></td>
            <td style="border: 1px solid black"><strong> Exterior </strong></td>
            <td style="border: 1px solid black"><strong> Tires </strong></td>
            <td style="border: 1px solid black"><strong> Financing </strong></td>
            <td style="border: 1px solid black"><strong> Additional Notes: </strong></td>
            <!-- @foreach($column_names as $column_name)
				<td style="border: 1px solid black"><strong> {{ $column_name }} </strong></td>
			@endforeach -->
		</tr>
	</thead>
	<tbody>
		@foreach($postings as $posting)
		<tr>
			<td width="13">{{ $posting->lot_number }}</td>

			<td width="30">{{ $posting->name }}</td>

			<!-- <td width="30">{{ $posting->description }}</td> -->
	
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Color')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Color')->first()->value : '' }}</td>
    
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Gear type')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Gear type')->first()->value : '' }}</td>

            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Plate Number')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Plate Number')->first()->value : '' }}</td>
    
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Mileage')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Mileage')->first()->value : '' }}</td>
    
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body Type')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body Type')->first()->value : '' }}</td>
        
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Fuel type')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Fuel Type')->first()->value : '' }}</td>
    
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Condition')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Condition')->first()->value : '' }}</td>
        
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body condition')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body condition')->first()->value : '' }}</td>
    
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Battery')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Battery')->first()->value : '' }}</td>
        
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Last Registration')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Last Registration')->first()->value : '' }}</td>

            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Field office')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Field office')->first()->value : '' }}</td>
    
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Interior')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Interior')->first()->value : '' }}</td>
        
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Exterior')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Exterior')->first()->value : '' }}</td>
        
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Tires')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Tires')->first()->value : '' }}</td>
        
            <td width="10">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Financing')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Financing')->first()->value : '' }}</td>
        
            <td width="30">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Additional Notes:')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Additional Notes:')->first()->value : '' }}</td>

            <!-- @foreach($column_names as $column_name)
				<td width="15">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', $column_name)->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', $column_name)->first()->value : '' }}</td>
			@endforeach -->
		</tr>
		@endforeach
	</tbody>
</table>
