<?php
/**
 * Librería auxiliar para manejo de datos binarios
 * @name Binary
 * @version 1.2
 */
abstract class Binary {
		
	function set( $new_stream ) {
		if (is_null( $new_stream ) || strlen( $new_stream ) == 0) { $this->stream = null; }
		elseif ( $this->isHex($new_stream) ) { $this->stream = pack("H*", $new_stream ); }
		else { $this->stream = $new_stream; }
	}

	function size($data) {
		return strlen($data);
	}

	function md5($data) {
		return strtoupper( md5( $data ));
	}
	
	function sha1($data) {
		return strtoupper( sha1( $data ));
	}
	
	function hex($data) {
		return strtoupper( bin2hex( $data ));
	}
	
	function base64($data) {
		return base64_encode( $data );
	}
	
	function bin($data) {
		$hex = bin2hex( $data );
		$packets = array();
		for($i=0; $i < strlen($hex); $i+=2) {
			$byte = substr($hex, $i, 2);
			$packets[] = sprintf("%'08s", base_convert($byte, 16, 2));
		}
		return $packets;
	}
	
	function oct($data) {
		$hex = bin2hex( $data );
		$packets = array();
		for($i=0; $i < strlen($hex); $i+=2) {
			$byte = substr($hex, $i, 2);
			$packets[] = sprintf("%'03s", base_convert($byte, 16, 8));
		}
		return $packets;
	}
	
	public function is() {
		
	}
	
	private function isHex( $hex ) {
    	$hex = preg_replace('/^(0x|X)?/i', '', $hex);
    	$hex = preg_replace('/[[:blank:]]/', '', $hex);
    	if(empty($hex)) { return false; }
    	if(!preg_match('/^[0-9a-fA-F]*$/i', $hex)) {
        	return false;
    	}
    	return true;
	}
}
?>