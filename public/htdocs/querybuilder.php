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
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

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
			
			<h4>Objectory Object > Create</h4>
			
			
			
			
			<form action="api/v1/" method="POST" target="response">
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
			
			
		</div>
		
		
		
		<div class="one-third column">
			<?php require_once('_public_sidebar.php') ?>
		</div>
		
		
	

	</div><!-- container -->



	<!-- JS
	================================================== -->
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="javascripts/tabs.js"></script>
	<?php require_once('_track.php'); ?>

<!-- End Document
================================================== -->
</body>
</html>