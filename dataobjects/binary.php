<?php
/**
 * @name Binary
 * @package framework.dataobjects
 * Objeto manejador de cadenas binarias
 * @author DSD
 * @version 1.2
 */

class Binary {
	private $stream = null;

	function __construct($stream = null) {
		if ( $stream ) {
			$this->set( $stream );
		}
	}

	function clear() { $this->stream = null; }
	function set( $new_stream ) {
		if (is_null( $new_stream ) || strlen( $new_stream ) == 0) { $this->stream = null; }
		elseif ( $this->isHex($new_stream) ) { $this->stream = pack("H*", $new_stream ); }
		else { $this->stream = $new_stream; }
	}
	function get() { return $this->stream; }
	function __toString() {	return $this->stream; }

	function size() {
		return strlen($this->stream);
	}

	function get_sql() {
		global $f;
		if ( $this->size() == 0 ) { return "NULL"; }
		$str = $f->database->get_escaped( $this->stream );
		$str = $f->database->get_quoted( $str );
		return $str;
	}
	
	function get_sql_hex() {
		global $f;
		if ( $this->size() == 0 ) { return "NULL"; }
		$str = $f->database->get_quoted( strtoupper( bin2hex($this->stream)) );
		return $str;
	}

	function get_md5() { return strtoupper( md5( $this->stream )); }
	function get_sha1() { return strtoupper( sha1( $this->stream )); }
	
	function get_hex() { return strtoupper( bin2hex( $this->stream )); }
	function get_base64() { return base64_encode( $this->stream ); }
	function get_bin() {
		$hex = bin2hex( $this->stream );
		$packets = array();
		for($i=0; $i < strlen($hex); $i+=2) {
			$byte = substr($hex, $i, 2);
			$packets[] = sprintf("%'08s", base_convert($byte, 16, 2));
		}
		return $packets;
	}
	function get_oct() {
		$hex = bin2hex( $this->stream );
		$packets = array();
		for($i=0; $i < strlen($hex); $i+=2) {
			$byte = substr($hex, $i, 2);
			$packets[] = sprintf("%'03s", base_convert($byte, 16, 8));
		}
		return $packets;
	}

	function set_hex( $hex ) {
    	if (is_null($hex) || strlen($hex) == 0) { $this->stream = null; }
		elseif (! $this->isHex($hex) ) {
			trigger_error('Argument is not hexadecimal', E_USER_WARNING);
			return false;
		}
		else { $this->stream = pack("H*", $hex ); }
	}
	function set_base64( $str ) {
		$this->stream = base64_decode( $str );
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