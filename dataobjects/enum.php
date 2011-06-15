<?php
/**
 * @name Enum
 * @package framework.dataobjects
 * Objeto manejador de enumeraciones
 * @author DSD
 * @version 1.0
 */

class Enum {
	private $values;
	private $key;

	function __construct( $values, $key = null ) {
		$this->values = array();
		if ( is_array( $values ) ) { $this->values = $values; }
		if ( !is_null( $key ) && array_key_exists( $key, $this->values ) ) { $this->key = $values; }
	}

	function __toString() {	return (string) $this->key; }

	function length() { return count( $this->values ); }

	function get() { return (string) $this->key; }

	function get_value() { return is_null($this->key) ? null : $this->values[$this->key]; }

	function get_html() { return is_null($this->key) ? null : htmlentities( $this->values[$this->key], ENT_QUOTES ); }

	function get_list() {
		$list = $this->values;
		foreach ($list as $k => $v) {
			$list[$k] = htmlentities( $v, ENT_QUOTES );
		}
		return $list;
	}

	function get_sql() {
		global $f;
		if ( is_null( $this->key ) ) { return "NULL"; }
		$str = utf8_decode( $this->key );
		$str = $f->database->get_escaped( $str );
		$str = $f->database->get_quoted( $str );
		return $str;
	}

	function set( $key ) {
		if ( is_null($key) ) { $this->key = null; }
		elseif ( array_key_exists( $key, $this->values ) ) { $this->key = $key; }
	}

	function is_valid( $key ) {
		return array_key_array( $key, $this->values );
	}


	/*
	funcion si se desea acceder a los valores de las constantes desde el mismo objeto.
	se debe pegar esta funcion en la clase base

	public static function __get($name) {
		if(defined("self::$name")) {
			return constant("self::$name");
		}
		trigger_error ("Constant $name isn't defined");
	}
	*/
}

?>