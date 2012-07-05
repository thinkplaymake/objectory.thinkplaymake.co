<?php

	// includes
	require_once('_localconfig.php');
	require_once('objectory.classes.php');
	//header("Content-type: text/plain");
	
	
	// create API
	$objectory = new objectory_api();
	
	
	// Default Calls.
	$request_type = strtolower( $_SERVER['REQUEST_METHOD'] );
	$request_method = $_REQUEST['method'];
	if (!in_array( $request_type, array( 'post', 'get' ) ) ) $objectory->throwError( 'Unexpected request type.' );
	
	// Validate writes by API Key
	if ($request_type == 'post') {
		if (isset($_POST['api_key']) && $_POST['api_key']) {
			$objectory->validateAPIKey( $_POST['api_key'] );
		} else {
			$objectory->throwError( 'No valid API key sent.' );
		}
	}
	
	
	
	// Run API
	
	
	// GET / = GET_OBJECTS_LIST
	if ($request_type == 'get' && $request_method == 'objects') {
		$objectory->get_objects();
	}
	
	
	// POST / = POST_OBJECT_CREATE
	if ($request_type == 'post' && $request_method == 'objects') {
		$objectory->post_objects( $_POST );
	}
	
	// GET /object/ABC = GET OBJECT
	if ($request_type == 'get' && $request_method == 'object') {
		$objectory->get_object( $_GET['id'] );
	}
	
	
	
	// end
	header("HTTP/1.0 404 Not Found");
	$objectory->throwError( 'Resource not found.' );
	
	
	
	