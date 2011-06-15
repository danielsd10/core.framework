<?php
/**
 * @name String
 * @package framework.dataobjects
 * Objeto manejador de cadenas de texto
 * @author DSD
 * @version 1.0
 */

class String {
	private $string = null;

	function __construct($string = null) {
		if ( $string ) {
			$this->string = (string) $string;
		} else {
			$this->string = "";
		}
	}

	function clear() { $this->string = null; }
	function set( $new_value ) { $this->string = (string) $new_value; }
	function get() { return $this->string; }
	function __toString() {	return $this->string; }

	function length() {
		return strlen($this->string);
	}

	function get_html() {
		$str = htmlentities( $this->string, ENT_QUOTES );
		$str = nl2br( $str );
		return $str;
	}
	
	function get_encoding() {
    return mb_detect_encoding($this->string, "UTF-8, ISO-8859-1");
	}

	function get_utf8() {
		return (mb_detect_encoding($this->string, "UTF-8, ISO-8859-1") == "ISO-8859-1") ? iconv("ISO-8859-1", "UTF-8", $this->string) : $this->string;
	}
	
	function get_latin1() {
    return (mb_detect_encoding($this->string, "UTF-8, ISO-8859-1") == "UTF-8") ? iconv("UTF-8", "ISO-8859-1", $this->string) : $this->string;
	}

	function get_sql() {
		global $f;
		if ( $this->length() == 0 ) { return "NULL"; }
		$str = (mb_detect_encoding($this->string, "UTF-8, ISO-8859-1") == "UTF-8") ? iconv("UTF-8", "ISO-8859-1", $this->string) : $this->string;
		$str = $f->database->get_escaped( $str );
		$str = $f->database->get_quoted( $str );
		return $str;
	}

	function get_md5() { return md5( $this->string ); }
	function get_sha1() { return sha1( $this->string ); }
	function get_upper() { return strtoupper( $this->string ); }
	function get_lower() { return strtolower( $this->string ); }

	function set_md5() { $this->string = md5( $this->string ); }
	function set_sha1() { $this->string = sha1( $this->string ); }
	function set_upper() { $this->string = strtoupper( $this->string ); }
	function set_lower() { $this->string = strtolower( $this->string ); }
	function set_random($lenght = 6, $level = 0) {
		$str = "";
		for($i = 1; $i <= $lenght; $i++) {
			$part = mt_rand(0, (int) $level);
			switch ( $part ) {
				case 0: $str .= mt_rand(0, 9); break;
		    	case 1: $str .= chr(mt_rand(65, 90)); break;
		    	case 2: $str .= chr(mt_rand(97, 122)); break;
		    	default:
		    		$sub_part = mt_rand(0, 3);
		    		switch ( $sub_part ) {
		    			case 0: $str .= chr(mt_rand(33, 47)); break;
		    			case 1: $str .= chr(mt_rand(58, 64)); break;
		    			case 2: $str .= chr(mt_rand(91, 96)); break;
		    			case 3: $str .= chr(mt_rand(123, 126)); break;
		    		}
			}
		}
		$this->string = $str;
	}
}

?>