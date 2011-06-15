<?php
/**
 * @name Procedure
 * @package framework
 * Procedimiento para ejecución y recepción de datos a base de datos
 * @author DSD
 * @version 1.1.0
 */

class Procedure {

	/**
	 * Nombre de procedimiento
	 *
	 * @var string
	 */
	var $name		= null;

	/**
	 * Conjunto de parámetros
	 *
	 * @var array
	 */
	var $params		= null;

	/**
	 * Conjunto de resultados
	 *
	 * @var array
	 */
	var $results	= null;

	/**
	 * Constructor
	 *
	 * @param string $table nombre de tabla
	 */
	function __construct( $procedure ) {
		$this->name		= $procedure;
		$this->params 	= array();
		$this->results	= array();
	}

	/**
	 * Agrega una clave o parámetro
	 *
	 * @param string $key clave
	 * @param string $value valor
	 */
	function add_param( $param ) {
		$this->params[]	= $param;
	}

	/**
	 * Agrega varias claves o parámetros
	 *
	 * @param array $keys claves
	 */
	function add_params( $params = array() ) {
		if ( is_array( $params ) ) {
			$this->params = $params;
		}
	}
	
	/**
	 * Agrega un espacio para almacenar los registros que pueda devolver el procedimiento
	 *
	 * @param string $key clave
	 */
	function add_resultset( $key ) {
		$this->results[$key] = array();
	}
	
	/**
	 * Devuelve los registros almacenados
	 *
	 * @param string $key clave
	 * @return array
	 */
	function get_resultset( $key ) {
		if (! array_key_exists($key, $this->results) ) { return false; }
		return $this->results[$key];
	}
	
	/**
	 * Ejecuta el procedimiento
	 *
	 * @return bool
	 */
	function execute() {
		global $f;
		$exec = $f->database->execute_call( $this->name, $this->params );
		if ( $exec === false ) { return false; }
		foreach ( array_keys($this->results) as $key ) {
			while ( true ) {
				$record = $f->database->get_record();
				if ( !$record ) { break; }
				$this->results[$key][] = $record;
			}
			if (! $f->database->next_result() ) { break; }
		}
		$f->database->clear();
		return true;
	}
}
?>