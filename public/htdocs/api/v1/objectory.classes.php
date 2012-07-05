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
	
		function get_objects() {
			
			$args = array( );
			$this->dbconnect();
			$cursor = $this->db->object->find( )->sort( array('timestamp'=>-1) )->limit( 5 );
			$results = iterator_to_array($cursor);
			
			$this->setMessage( 'Objectory Object List retrieved' );
			$this->setPayload( $results );
			$this->respond();
			
		}
		
		function post_objects( $post_data ) {
			
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
			
			$objectory_object->timestamp = date('r');
			$objectory_object->author_ip = $_SERVER['REMOTE_ADDR'];
			$objectory_object->client_apikey = $this->client_apikey;
						
			
			// connect to db.
			$this->dbconnect();
			
			// save object
			$this->db->object->insert( $objectory_object );
			if(isset($objectory_object->_id)) {
				$this->setMessage( 'Objectory Object created' );
				$this->setPayload( $objectory_object );
				$this->respond();
			} else {
				$this->throwError( 'Failed to insert object (unknown reason)' );
			}
			
	
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
		
	}
	
		
		
	
	class objectory_response_error extends objectory_response {
		function __construct( $msg='' ) {
			if ($msg) $this->throwError( $msg );
		}
		
	}
	
	