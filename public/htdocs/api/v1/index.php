<?php

	// includes
	require_once('_localconfig.php');
	require_once('objectory.classes.php');

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$request_type = 'post';
		$request_method = 'objectory_object_create';
	} else {
		new objectory_api_error( 'Unsupported request type for this resource.' );
	}
	
	
	
	
	if ($request_type == 'post' && $request_method == 'objectory_object_create') {
		
		if (isset($_POST['description']) && $_POST['description'] && isset($_POST['type']) && $_POST['type']) {
			
			// create basic object
			$objectory_object = new stdClass;
			$objectory_object->description = strip_tags( $_POST['description'] );
			$objectory_object->type = strip_tags( $_POST['type'] );
			
			$objectory_object->timestamp = date('r');
			$objectory_object->author = $_SERVER['REMOTE_ADDR'];
			$objectory_object->testdata = OBJECTORY_TESTDATAFLAG;
			
			
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
			
			
		} else {
			new objectory_api_error( 'Failed to create new objectory object, as there was missing data' );
			
		
		}
	}
		
	
	
	
	
	
	