<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>objectory / query builder</title>
	<meta name="description" content="query builder for using the API">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="../../stylesheets/base.css">
	<link rel="stylesheet" href="../../stylesheets/skeleton.css">
	<link rel="stylesheet" href="../../stylesheets/layout.css">
	<style>
		tt { font-family: monospace; display: inline; }
		.api_calls li { border-bottom: 2px dotted #ccc; margin-bottom: 15px; padding-bottom: 15px; }
		.api_calls h4 { background: #eee; }
	</style>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="../images/favicon.ico">
	<link rel="apple-touch-icon" href="../images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../images/apple-touch-icon-114x114.png">

</head>
<body>



	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	<div class="container">
		<div class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px;"><span>objectory</span> - API Query Builder</h1>
			<hr />
		</div>
		
		
		
		<div class="nine columns">
			
			<ul class="api_calls">
				<li>
					<h4>Objectory Objects > Fetch</h4>
					<p class="method_description">Fetches five most recently updated objects</p>
					<p class="call_instruction"><a href="./" target="_blank">GET from <tt>/</tt></a></p>
				</li>
				
				<li>
			
					
					<h4>Objectory Objects > Create</h4>
					<p class="method_description">Adds a simple object to the objectory</p>
					
					<form action="./" method="POST" target="response">
					
						<p class="call_instruction">POST to <tt>/</tt></p>
					
						<label for="description">API Key:</label> 
						<input name="api_key" value="test" />
					
						<label for="description">Description:</label> 
						<input name="description" value=""/ >
						
					
						<label for="type">Type:</label> 
						<select name="type">
							<option></option>
							<option>camera</option>
							<option>book</option>
							<option>toy</option>
							<option>story</option>
							<option>other</option>
						</select>
						
						<input type="submit">
						
					</form>
				</li>
				
				<li>
					<h4>Objectory Object > Fetch</h4>
					<p class="method_description">Fetches specific object and its stories</p>
					<p class="call_instruction"><a href="./object/4ff8a2618019cc008d000002" target="_blank">GET from <tt>/object/{OBJECTID}</tt></a></p>
					
				</li>
				
				<li>
					<h4>Objectory Story > Create</h4>
					<p class="method_description">Adds a story to a defined Objectory Object</p>
					<p class="call_instruction">POST to <tt>/object/{OBJECTID}</tt></p>
					
					<form action="./object/4ff8a2618019cc008d000002" method="POST" target="response">
					
						<label for="description">API Key:</label> 
						<input name="api_key" value="test" />
						
						<label for="location">Location (lat, lng):</label> 
						<input name="location_lat" placeholder="location_lat" value="51"/ >
						<input name="location_lng" placeholder="location_lng" value="-0.1"/ >
						
						<label for="description">Description:</label> 
						<input name="description" value="test description"/ >
						
						<label for="location">Owner (fname, sname, email):</label> 
						<input name="owner_fname" placeholder="owner_fname" value="john"/ >
						<input name="owner_sname" placeholder="owner_sname" value="doe"/ >
						<input name="owner_email" placeholder="owner_email" value="john@example.com"/ >
						
						
						
						<p style="margin-top: 15px;"><input type="submit"></p>
						
					</form>
					
				</li>
				
			</ul>
			
			
			
			
			
			
		
			
			
		</div>
		
		
		
		<div class="one-third column">
			<?php require_once('../../_public_sidebar.php') ?>
		</div>
		
		
	

	</div><!-- container -->



	<!-- JS
	================================================== -->
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="../../javascripts/tabs.js"></script>
	<?php require_once('../../_track.php'); ?>

<!-- End Document
================================================== -->
</body>
</html>