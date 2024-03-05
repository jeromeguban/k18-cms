<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="UTF-8">
  	<title>CMS | Details Registration</title>
  	<link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
  	<style>
  		*
		{
			font-family: 'Rubik', sans-serif;
			padding: 0;
			margin: 0;
			font-size: 14px;
		}
		p
		{
			line-height: 1.5;
		}
		.shadow
		{
			box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),0 4px 6px -2px rgba(0, 0, 0, 0.05);
		}
  	</style>
  </head>
  <body style="background-color: #f5f5f9;">
      <div style="width: 100%; height: 100%; display: flex; flex-direction: column; width: 500px; margin: 0 auto; padding-top:30px; padding-bottom: 10px; background-color: #f5f5f9;">
      <div style="border-top-width: 4px; border-top-style: solid; border-color: #1d3faa;"></div>
      <div class="shadow" style="background: white; padding:30px 0; display: flex; align-items: center; justify-content: center;">
      	<div style="width: 100%; width:100%; background:white;padding:30px;">
			<div style="display: flex; justify-content: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
				<img src="http://hmrbid.com/hammer-images/logos/5b21cc38d33b6.jpg" width="130"  alt="">
			</div>

      		<p style="font-weight: 900; font-size: 20px;">{{ $subject }}</p>
      		<p style="display: block; margin-top: 10px;">{{ $text }}</p>
      		<div style="text-align:center; padding: 20px 0 10px;">
				<a href="{{ $action }}" style="background: #1d3faa; color: white; border: none; padding: 10px 50px; font-size: 15px; border-radius: 3px; cursor: pointer; text-decoration: none;">{{ $action_text }}</a>
			</div>
      		<p style="color: #718096; margin-top: 20px;">
				If the button above is not working. Please click this <a href="{{ $action }}" style="color: #ff6a23; text-decoration: underline;">link</a>. If both are not working please copy and paste the url below to your browser.
			</p>
      	</div>

      </div>
      <div style="border-top-width: 4px; border-top-style: solid; border-color: #1d3faa;"></div>

      </div>
  </body>
</html>
