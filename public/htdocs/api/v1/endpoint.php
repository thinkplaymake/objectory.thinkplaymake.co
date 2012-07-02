<?php

	// includes
	require_once('_localconfig.php');
	require_once('objectory.classes.php');

	// only acceptable method is a POST to this file
	
	// generate random crap
	$_POST['description'] = rand();
	
	
	
	
	if (isset($_POST['description'])) {
		
		// create basic object
		$objectory_object = new stdClass;
		$objectory_object->description = $_POST['description'];
		$objectory_object->timestamp = date('r');
		
		
		// connect to db.
		try {
			$m = new Mongo(MONGO_DB); // connect
			$db = $m->selectDB("objectory");
		} catch ( Exception $e ) {
			new objectory_api_error( 'Failed to connect to MongoLab database' );
		}
		
		// save object
		$db->object->insert( $objectory_object );
		if(isset($objectory_object->_id)) {
			$response = new objectory_api_response(  );
			$response->setMessage( 'Objectory Object created' );
			$response->setPayload( $objectory_object );
			$response->respond();
		} else {
			new objectory_api_error( 'Failed to insert object (unknown reason)' );
		}
		
		
	}
	
	
	
	
	
	
	