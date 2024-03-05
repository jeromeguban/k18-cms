<html>
	<head>
		<title>Auction Event - {{ $auction->auction_number }}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css">
			#page-wrap {
				margin: 0 auto;
			}

			table {
				border-collapse: collapse;
			}	

			table td {
				padding: 3%px 8%px;
			}

			table thead tr td {
				text-transform: uppercase;
				letter-spacing: 1px; 
				font-size: 12px;
			}

			table tbody tr td {
				font-size: 12px;
			}

			th {
				font-size: 12px;
			}
		</style>
	</head>
	<body>
		<div id="page-wrap">
            <table width="100%">
				<tr>
					<td align="center">
						<div style="font-size: 16px; font-weight: 800;">HMR AUCTION SERVICES INC</div>
						<div style="font-size: 12px ">{{$store->address_line}}</div>
						<div style="font-size: 13px; font-weight: 800;">Auction Event - {{ $auction->auction_number }}</div>
                        <div style="margin-top: 3%px;"></div>
					</td>
				</tr>
			</table>
			<table width="100%" style="padding-bottom: 5px;">
                <thead>
                    <tr style="border: 1px strong #000">
                        <th style="border-bottom: 1px solid black;"><strong> Lot # </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Name </strong></th>
                        <!-- <th style="border-bottom: 1px solid black;"><strong> Unit Description </strong></th> -->
                        <th style="border-bottom: 1px solid black;"><strong> Color </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Gear type </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Plate# </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Mileage </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Body Type </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Fuel Type </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Condition </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Body Condition </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Battery </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Last Registration </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Field Office </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Interior </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Exterior </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Tires </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Financing </strong></th>
                        <th style="border-bottom: 1px solid black;"><strong> Notes: </strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($postings as $posting)
                    <tr>
                        <td width="5%">{{ $posting->lot_number }}</td>

                        <td width="20%">{{ $posting->name }}</td>

                        <!-- <td width="10%">{{ $posting->description }}</td>
                 -->
                        <td width="5%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Color')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Color')->first()->value : '' }}</td>
                
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Gear type')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Gear type')->first()->value : '' }}</td>

                        <td width="5%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Plate Number')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Plate Number')->first()->value : '' }}</td>
                
                        <td width="5%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Mileage')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Mileage')->first()->value : '' }}</td>
                
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body Type')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body Type')->first()->value : '' }}</td>
                    
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Fuel type')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Fuel Type')->first()->value : '' }}</td>
                
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Condition')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Condition')->first()->value : '' }}</td>
                    
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body condition')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Body condition')->first()->value : '' }}</td>
                
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Battery')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Battery')->first()->value : '' }}</td>
                    
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Last Registration')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Last Registration')->first()->value : '' }}</td>

                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Field office')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Field office')->first()->value : '' }}</td>
                
                        <td width="5%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Interior')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Interior')->first()->value : '' }}</td>
                    
                        <td width="5%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Exterior')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Exterior')->first()->value : '' }}</td>
                    
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Tires')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Tires')->first()->value : '' }}</td>
                    
                        <td width="10%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Financing')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Financing')->first()->value : '' }}</td>
                    
                        <td width="20%">{{ $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Additional Notes:')->first() ? $posting_attributes->where('posting_id', $posting->posting_id)->where('column_name', 'Additional Notes:')->first()->value : '' }}</td>
                    
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
	</body>
</html>