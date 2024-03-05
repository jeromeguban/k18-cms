<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Your Access has been Approved | HMR</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<style>
		*{
			padding: 0;
			font-family: "Roboto";
			margin: 0;
			color: #444;
		}

		a{
			color: #003663;
			text-decoration: underline;
			font-weight: bold;
			font-style: italic;
		}

		.email{
			width: 800px;
			margin: 0 auto;
		}

		.email-header{
			margin-top: 10px;
			border-bottom: 2px solid #ebebeb;
			padding-bottom: 20px;
			margin-bottom: 20px;
		}

		.email-type{
			color: #fd7936;
			float: right;
			font-weight: 800;
			margin-top: 15px;
		}

		.logo-container{
			width: 60%;
			float: left;
		}

		.address{
			font-color: #7d7d7d;
			font-weight: 800;
		}

		.message{
			margin-top: 20px;
			line-height: 1.4;
		}
		.orange{
			color: #fd7936;
		}
		.clear{
			clear: both;
		}
		.footer{
			margin-top: 20px;
			padding: 40px 20px;
			background-color: #003663;
			line-height: 1.6;
			margin-bottom: 30px;
		}

		.email-footer-nav{
			margin-top: 10px;
		}

		.contact-us h3{
			margin-bottom: 20px;
		}

		.contact-us p{
			color: #fff;
			font-size: 14px;
			line-height: 1.8;
		}

		.email-footer-nav ul li{
			padding: 3px 0;
		}

		.email-footer-nav ul li a{
			color: #fff;
			font-weight: normal;
			text-decoration: none;
			font-style: normal;
			font-size: 14px;
		}
	</style>
</head>
<body>
	<div class="email" style="border: 1px solid #ddd; padding: 20px; border-top:5px solid #fd7936; border-bottom:5px solid #fd7936">
			<div class="email-header">
				<div class="logo-container">
					<a href="" style="padding-bottom: 20px;"><img src="https://hmr.ph/images/hmr-ph-logo.png" style="height:40px;"></a>
					<p class="address" style="font-size: 14px; clear: both; margin-top: 10px;">{{ $store['address_line'] }}</p>
				</div>
				<span class="email-type" style="text-transform: uppercase; text-decoration: underline;">Your Access has been Approved.</span>
				<div style="clear: both;"></div>
			</div>
			<div class="email-body">
				<h3>Dear {{ $access_request['customer_firstname'] }} {{ $access_request['customer_lastname'] }},</h3>
				<p class="message" style="line-height: 1.4">
				 <span style="color:#003663; font-weight: 800; ">Congratulations!</span>
				<br><p>{!! $template['body'] !!}</p><br>
				Click on link below:<br>
				<a href="{{ $action }}" style="color:#003663; font-weight: 800; text-decoration: underline;"> {{ $action }} </a>
				<br><br>
				For further assistance please call our Customer Service at <a href="" style="color:#003663; font-weight: 800; text-decoration: underline;">  09398145233 / 09691371013 </a>
				<br><br>
				Thank you! <br>
				<span style="color:#fd7936; font-weight: 900;  font-size: 25px;">HMR</span>
				<p>
				<br>
				</p>


			</div>	
	</div>
	
</body>
</html>