<html>
	<head>
		<title>Event Catalogue</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css">
			#page-wrap {
				margin: 0 auto;
			}

			table {
				border-collapse: collapse;
			}

			table td {
				padding: 1rem 10px;
			}

			table thead tr td {
				text-transform: uppercase;
				letter-spacing: 1px;
				font-size: 12px;
			}

            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }

			table tbody tr td {
				font-size: 12px;
			}

			th {
				font-size: 13px;
			}
		</style>
		</style>
	</head>
	<body>
		<div id="page-wrap">
            <center>
                <div style="font-size: 16px; font-weight: 800; margin-bottom: 2rem">Product Catalog - {{$event->event_name}}</div>
            </center>

			<table width="100%">
				<thead>
					<tr>
                        {{-- <th width="15%">Image</th> --}}
                        <th width="20%">Product Name </th>
                        <th width="40%">Description </th>
                        <th width="15%">Unit Price </th>
                        <th width="15%">Suggested Retail Price </th>
					</tr>
				</thead>
				<tbody style="border-top: 1px solid black;">
					@foreach($postings as $posting)
					<tr>
                       {{-- <td>
                            <img src="{{$posting->banner}}" alt="" style="max-width: 220px; max-height: 175px; margin-top:5px">
                        </td> --}}
                       <td> {{ $posting->name }} </td>
                       <td> {!! $posting->description !!} </td>
                       <td> {{ number_format($posting->unit_price, 2, '.', ',') }} </td>
                       <td> {{ number_format($posting->suggested_retail_price, 2, '.', ',') }} </td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</body>
</html>
