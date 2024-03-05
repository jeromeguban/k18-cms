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
            width: 1000px;
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
</head>

<body>
    <div class="email" style="border: 1px solid #ddd; padding: 20px; border-top:5px solid #fd7936; border-bottom:5px solid #fd7936">
        <div class="email-header">
            <div class="logo-container">
                <a href="" style="padding-bottom: 20px;"><img src="https://hmr.ph/images/hmr-ph-logo.png" style="height:40px;"></a>
                <!-- <p class="address" style="font-size: 14px; clear: both; margin-top: 10px;"> </p> -->
            </div>
            <center>
                <span class="email-type" style="text-transform: uppercase; text-decoration: underline;">{{$cart['name']}}</span>
            </center>
            <div style="clear: both;"></div>
        </div>
        <div class="email-body">
        We noticed that you left items in your cart! We understand things come up, but we wanted to remind you that your items are still waiting for you. <br>
        Don't miss out on these great deals.<br><br><br>
        <!-- Follow the link to complete your purchase now!<br> -->
        Thank you for shopping with us.
        </div>
    </div>
</body>

</html>
