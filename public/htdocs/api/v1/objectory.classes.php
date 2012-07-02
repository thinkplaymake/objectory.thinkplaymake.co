<?php
	
	
	
	class objectory_api_response {
		private $_message;
		private $_type;
		private $_payload;
		
		
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
			print json_encode( $response );
			exit();
		}
	}
	
	class objectory_api_error extends objectory_api_response {
		function __construct( $msg='' ) {
			if ($msg) {
				$this->setType( 'error' );
				$this->setMessage ( $msg );
				$this->respond();
			}
		}
	}
	
	