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
		
		function respond( $indent=0 ) {
			$response = new stdClass();
			$response->type = $this->getType();
			$response->message = $this->getMessage();
			$response->payload = $this->getPayload();
			$response->hal_says = 'Dave, this conversation can serve no purpose anymore. Goodbye.';
			if ( $indent == 1 ) {
				print $this->indent( json_encode( $response ) );
			} else {
				print json_encode( $response );
			}
			exit();
		}
		
		function throwError($msg) {
			$this->setType( 'error' );
			$this->setMessage ( $msg );
			$this->respond();
		}
		
		function indent($json) {

			$result      = '';
			$pos         = 0;
			$strLen      = strlen($json);
			$indentStr   = '  ';
			$newLine     = "\n";
			$prevChar    = '';
			$outOfQuotes = true;

			for ($i=0; $i<=$strLen; $i++) {

				// Grab the next character in the string.
				$char = substr($json, $i, 1);

				// Are we inside a quoted string?
				if ($char == '"' && $prevChar != '\\') {
					$outOfQuotes = !$outOfQuotes;
				
				// If this character is the end of an element, 
				// output a new line and indent the next line.
				} else if(($char == '}' || $char == ']') && $outOfQuotes) {
					$result .= $newLine;
					$pos --;
					for ($j=0; $j<$pos; $j++) {
						$result .= $indentStr;
					}
				}
				
				// Add the character to the result string.
				$result .= $char;

				// If the last character was the beginning of an element, 
				// output a new line and indent the next line.
				if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
					$result .= $newLine;
					if ($char == '{' || $char == '[') {
						$pos ++;
					}
					
					for ($j = 0; $j < $pos; $j++) {
						$result .= $indentStr;
					}
				}
				
				$prevChar = $char;
			}

			return '<pre>' . $result . '</pre>';
		}
		
		
	}
	
	class objectory_api extends objectory_response {
	
		var $client_apikey;
		
		
		function filter_spec() {
			return array(	'author_ip'=>0, 
							'client_apikey'=>0,
							'stories.owner.email'=>0,
							'stories.author_ip'=>0,
							'stories.client_apikey'=>0,
						);
		}
	
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
			$cursor = $this->db->object->find( array(), $this->filter_spec() )->sort( array('timestamp_updated'=>-1) )->limit( 5 );
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
			
			$where = array( '_id' => new MongoId($id) );			
			$objectory_object = $this->db->object->findOne( $where, $this->filter_spec());
			if (isset($objectory_object['_id']->{'$id'}) && $objectory_object['_id']->{'$id'} == $id) {
				$this->setPayload( $objectory_object );
				$this->setMessage( 'Found object' );
			} else {
				$this->throwError( 'Object not found' );
			}
			
			$this->respond(1);
			
		}
		
		/*
			attaches a story to an object
		*/
		function post_story ( $post_data ) {
			//story object is location_lat, location_lng, description, owner_fname, owner_sname, owner_email
			
			// sanitize inputs (it isn't sanitzing just yet..)
			$location = array( (float)$_POST['location_lat'], (float)$_POST['location_lng'] );
			if (!$location[0] || $location[0] > 180 || $location[0] < -180 || !$location[1] || $location[1] > 180 || $location[1] < -180) $this->throwError( 'invalid location' );
			
			
			$description = (string)htmlentities($_POST['description']);
			if (!$description) $this->throwError( 'missing description' );
			
			
			$owner = array( 'fname'	=>	(string)$_POST['owner_fname'],
							'sname'	=>	(string)$_POST['owner_sname'],
							'email'	=>	(string)$_POST['owner_email'] );
			if (!$owner['fname'] || !$owner['sname'] || !$owner['email']) {
				$this->throwError( 'missing owner details' );
			}
			
			
			
			
			// find object
			$this->dbconnect();
			$object_id = (string)trim( $_GET['id'] );

			$objectory_object_id = new MongoID( $object_id );
			$objectory_object = $this->db->object->findOne( array('_id'=> $objectory_object_id) );
			if( (string)$objectory_object['_id']  != $objectory_object_id ) $this->throwError( 'invalid object id' );
			
			
			// create story
			$objectory_story = new stdClass();
			$objectory_story->location = $location;
			$objectory_story->description = $description;
			$objectory_story->owner = $owner;
			
			$objectory_story->timestamp_created = date('r');
			$objectory_story->author_ip = $_SERVER['REMOTE_ADDR'];
			$objectory_story->client_apikey = $this->client_apikey;
			
		
			
			
			
			
			// update object
			$objectory_object['timestamp_updated'] = date('r');
			if (!isset($objectory_object['stories'])) $objectory_object['stories'] = array();
			array_unshift( $objectory_object['stories'], $objectory_story );
			
			$this->db->object->update( array('_id'=>$objectory_object['_id']), $objectory_object );
			
	
			if ((string)$objectory_object['_id'] != $object_id) {
				$this->throwError( 'Failed to insert story (unknown reason)' );
			}
			
			$this->setMessage( 'Objectory Story added' );
			$this->setPayload( $this->get_object( $object_id ) );
			$this->respond();
			
			
			exit();
		}
		
	}
	
		
		
	
	class objectory_response_error extends objectory_response {
		function __construct( $msg='' ) {
			if ($msg) $this->throwError( $msg );
		}
		
	}
	
	