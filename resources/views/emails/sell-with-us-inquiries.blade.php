<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sell with Us Inquiries | HMR</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            font-family: "Roboto";
            margin: 0;
            color: #444;
        }

        a {
            color: #003663;
            text-decoration: underline;
            font-weight: bold;
            font-style: italic;
        }

        .email {
            width: 1400px;
            margin: 0 auto;
        }

        .email-header {
            margin-top: 10px;
            border-bottom: 2px solid #ebebeb;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .email-type {
            color: #fd7936;
            float: right;
            font-weight: 800;
            margin-top: 15px;
        }

        .logo-container {
            width: 60%;
            float: left;
        }

        .address {
            font-color: #7d7d7d;
            font-weight: 800;
        }

        .message {
            margin-top: 20px;
            line-height: 1.4;
        }

        .orange {
            color: #fd7936;
        }

        .clear {
            clear: both;
        }

        .footer {
            margin-top: 20px;
            padding: 40px 20px;
            background-color: #003663;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .email-footer-nav {
            margin-top: 10px;
        }

        .contact-us h3 {
            margin-bottom: 20px;
        }

        .contact-us p {
            color: #fff;
            font-size: 14px;
            line-height: 1.8;
        }

        .email-footer-nav ul li {
            padding: 3px 0;
        }

        .email-footer-nav ul li a {
            color: #fff;
            font-weight: normal;
            text-decoration: none;
            font-style: normal;
            font-size: 14px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        button {
            width: 145px;
            padding: 16px 8px;
            margin: 0 8px;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            background-color: #fd7936;

        }
    </style>

    <script type="text/javascript">
        function tableToCSV() {

            // Variable to store the final csv data
            var csv_data = [];

            // Get each row data
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {

                // Get each column data
                var cols = rows[i].querySelectorAll('td,th');

                // Stores each csv row data
                var csvrow = [];
                for (var j = 0; j < cols.length; j++) {

                    // Get the text data of each cell
                    // of a row and push it to csvrow
                    csvrow.push(cols[j].innerHTML);
                }

                // Combine each column value with comma
                csv_data.push(csvrow.join(","));
            }

            // Combine each row data with new line character
            csv_data = csv_data.join('\n');

            // Call this function to download csv file
            downloadCSVFile(csv_data);

        }

        function downloadCSVFile(csv_data) {

            // Create CSV file object and feed
            // our csv_data into it
            CSVFile = new Blob([csv_data], {
                type: "text/csv"
            });

            // Create to temporary link to initiate
            // download process
            var temp_link = document.createElement('a');

            // Download csv file
            temp_link.download = "Sell With Us Inquiries.csv";
            var url = window.URL.createObjectURL(CSVFile);
            temp_link.href = url;

            // This link should not be displayed
            temp_link.style.display = "none";
            document.body.appendChild(temp_link);

            // Automatically click the link to
            // trigger download
            temp_link.click();
            document.body.removeChild(temp_link);
        }
    </script>
</head>

<body>
    <div class="email" style="border: 1px solid #ddd; padding: 20px; border-top:5px solid #fd7936; border-bottom:5px solid #fd7936">
        <div class="email-header">
            <div class="logo-container">
                <a href="" style="padding-bottom: 20px;"><img src="https://hmr.ph/images/hmr-ph-logo.png" style="height:40px;"></a>
                <p class="address" style="font-size: 14px; clear: both; margin-top: 10px;"> From : {{ $from }} - To : {{$to}} </p>
            </div>
            <center>
                <span class="email-type" style="text-transform: uppercase; text-decoration: underline;">Sell with Us Inquiries (Weekly Summary List)</span>
            </center>
            <div style="clear: both;"></div>
        </div>
        <div class="email-body">
            <table>
                <thead>
                    <tr style="border: 1px thick #000">
                        <td style="border: 1px solid black"><strong>Name</strong></td>
                        <td style="border: 1px solid black"><strong>Email</strong></td>
                        <td style="border: 1px solid black"><strong>Mobile No.</strong></td>
                        <td style="border: 1px solid black"><strong>Address</strong></td>
                        <td style="border: 1px solid black"><strong>Subject</strong></td>
                        <td style="border: 1px solid black"><strong>Message</strong></td>
                        <td style="border: 1px solid black"><strong>Account Executive</strong></td>
                        <td style="border: 1px solid black"><strong>Status</strong></td>
                          <td style="border: 1px solid black"><strong>Task</strong></td>
                        <td style="border: 1px solid black"><strong>Date of Inquiry</strong></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $inquiry)
                    @php
                        $task = $tasks->where('posting_inquiry_id', $inquiry->id)->where('status', $inquiry->status)->first();
                    @endphp
                    <tr>
                        <td>{{ $inquiry->full_name }}</td>
                        <td>{{ $inquiry->email }}</td>
                        <td>{{ $inquiry->mobile_no }}</td>
                        <td>{{ $inquiry->address }}</td>
                        <td>{{ $inquiry->subject }}</td>
                        <td>{{ $inquiry->message }}</td>
                        <td>{{ $inquiry->account_executive }}</td>
                        <td>{{ $inquiry->status }}</td>

                             <td>
                                 @foreach(json_decode($task->task, true) as $key => $value)

                                    @if( $value['finished'] == true)
                                        {{ $value['item'] }} - Completed,
                                    @else
                                      {{ $value['item'] }} - In Progress,
                                    @endif
                                        <br>
                                  @endforeach
                            </td>

                        <td>{{ $inquiry->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- <br>
            <button type="button" onclick="tableToCSV()">
                Download CSV
            </button> -->

            <p>
                <br>
            </p>

        </div>
    </div>
</body>

</html>
