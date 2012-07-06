<?php
	
	
	class objectory {
		
		
		var $_message = '';
		var $_type = 'win';
		var $_payload = '';
		
		var $db;
	
		function dbconnect() {
			try {
				$m = new Mongo(MONGO_DB); // connect
				$this->db = $m->selectDB("objectory");
			} catch ( Exception $e ) {
				new objectory_api_error( 'Failed to connect to MongoLab database' );
			}
		}
		
	}
	
	class objectory_response extends objectory {
		function __construct() {
			$this->setType( 'win' );
		}
		function setMessage( $msg ) {
			$this->_message = $msg;
		}
		function getMessage() {
			return $this->_message;
		}
		function setType( $type ) {
			$this->_type = $type;
		}
		function getType() {
			return $this->_type;
		}
		function setPayload( $object ) {
			$this->_payload = $object;
		}
		function getPayload() {
			return $this->_payload;
		}
		
		function respond() {
			$response = new stdClass();
			$response->type = $this->getType();
			$response->message = $this->getMessage();
			$response->payload = $this->getPayload();
			$response->hal_says = 'Dave, this conversation can serve no purpose anymore. Goodbye.';
			print json_encode( $response );
			exit();
		}
		
		function throwError($msg) {
			$this->setType( 'error' );
			$this->setMessage ( $msg );
			$this->respond();
		}
		
		
	}
	
	class objectory_api extends objectory_response {
	
		var $client_apikey;
	
		function validateAPIKey($key) {
			if ($key == 'test') {
				$this->client_apikey = $key;
				return true;
			} else {
				$this->throwError('Invalid API key sent');
			}
		}
	
		function get_objectory() {
			
			$args = array( );
			$this->dbconnect();
			$cursor = $this->db->object->find( )->sort( array('timestamp_updated'=>-1) )->limit( 5 );
			$results = iterator_to_array($cursor);
			
			$this->setMessage( 'Objectory Object List retrieved' );
			$this->setPayload( $results );
			$this->respond();
			
		}
		
		function post_objectory( $post_data ) {
			
			// basic validation.
			if (	!isset( $post_data['description'] ) || 
					empty( $post_data['description'] ) || 
					!isset( $post_data['type'] ) || 
					empty( $post_data['type'] )
					) {
				$this->throwError( 'Missing required data for call' );
			}
			
			// create simple object
			$objectory_object = new stdClass;
			$objectory_object->description = strip_tags( $_POST['description'] );
			$objectory_object->type = strip_tags( $_POST['type'] );
			
			$objectory_object->timestamp_created = date('r');
			$objectory_object->author_ip = $_SERVER['REMOTE_ADDR'];
			$objectory_object->client_apikey = $this->client_apikey;
						
			
			// connect to db.
			$this->dbconnect();
			
			// save object
			$this->db->object->insert( $objectory_object );
			if(!isset($objectory_object->_id)) $this->throwError( 'Failed to insert object (unknown reason)' );
			
			$this->setMessage( 'Objectory Object created' );
			$this->setPayload( $objectory_object );
			$this->respond();
			
	
		}
		
		
		function get_object ( $id ) {
			$this->dbconnect();
			$objectory_object = $this->db->object->findOne( array( '_id' => new MongoId($id) ) );
			if (isset($objectory_object['_id']->{'$id'}) && $objectory_object['_id']->{'$id'} == $id) {
				
				// strip privates
				unset( $objectory_object [ 'author_ip' ] );
				unset( $objectory_object [ 'client_apikey' ] );
				
				$this->setPayload( $objectory_object );
				$this->setMessage( 'Found object' );
			} else {
				$this->throwError( 'Object not found' );
			}
			
			$this->respond();
			
		}
		
		/*
			attaches a story to an object
		*/
		function post_story ( $post_data ) {
			//story object is object_id, location_lat, location_lng, description, owner_fname, owner_sname, owner_email
			$this->dbconnect();
			
			
			// sanitize inputs (it isn't sanitzing just yet..)
			$object_id = (string)$_POST['object_id'];
			$location = array( (float)$_POST['location_lat'], (float)$_POST['location_lng'] );
			$description = (string)$_POST['description'];
			$owner = array( 'fname'	=>	(string)$_POST['owner_fname'],
							'sname'	=>	(string)$_POST['owner_sname'],
							'email'	=>	(string)$_POST['owner_email'] );
			
			
			$objectory_object_id = new MongoID( $object_id );
			
			// create story
			$objectory_story = new stdClass();
			$objectory_story->location = $location;
			$objectory_story->description = $description;
			$objectory_story->owner = $owner;
			
			$objectory_story->timestamp_created = date('r');
			$objectory_story->author_ip = $_SERVER['REMOTE_ADDR'];
			$objectory_story->client_apikey = $this->client_apikey;
			
			
			/*
			// save object
			$this->db->story->insert( $story );
			
			//$this->db->object->update(array('_id' => new MongoID($story->object_id)), $story);
			*/
			
			
			// find object
			
			$objectory_object = $this->db->object->findOne( array('_id'=> $objectory_object_id) );
			if( (string)$objectory_object['_id']  != $objectory_object_id ) $this->throwError( 'invalid object id' );
			
			// update object
			$objectory_object['timestamp_updated'] = date('r');
			if (!isset($objectory_object['stories'])) $objectory_object['stories'] = array();
			array_unshift( $objectory_object['stories'], $objectory_story );
			
			$this->db->object->update( array('_id'=>$objectory_object['_id']), $objectory_object );
			
			
			
			
			exit();
			if ($objectory_object->_id->{'$id'} = $story->object_id) {
				print "gotit";
			}
			
			
			if(!isset($story->_id)) {
				$this->throwError( 'Failed to insert story (unknown reason)' );
			}
			
			$this->setMessage( 'Objectory Story added' );
			$this->setPayload( $story );
			$this->respond();
			
			
			exit();
		}
		
	}
	
		
		
	
	class objectory_response_error extends objectory_response {
		function __construct( $msg='' ) {
			if ($msg) $this->throwError( $msg );
		}
		
	}
	
	