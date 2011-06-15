<?php
/**
 * @name Table
 * @package framework
 * Tabla para envío de datos a base de datos
 * @author DSD
 * @version 1.1.1
 */

class Table {

	/**
	 * Nombre de tabla
	 *
	 * @var string
	 */
	var $name		= null;

	/**
	 * Conjunto de claves o parámetros
	 *
	 * @var array
	 */
	var $keys		= null;

	/**
	 * Conjunto de campos y valores
	 *
	 * @var array
	 */
	var $fields		= null;

	/**
	 * Constructor
	 *
	 * @param string $table nombre de tabla
	 */
	function __construct( $table ) {
		$this->name		= $table;
		$this->keys	 	= array();
		$this->fields	= array();
	}

	/**
	 * Agrega una clave o parámetro
	 *
	 * @param string $key clave
	 * @param string $value valor
	 */
	function add_key( $key, $value ) {
		$this->keys[ $key ]	= $value;
	}

	/**
	 * Agrega varias claves o parámetros
	 *
	 * @param array $keys claves
	 */
	function add_keys( $keys = array() ) {
		if ( is_array( $keys ) ) {
			$this->keys = $keys;
		}
	}

	/**
	 * Agrega campos en base a las propiedades y valores de un objeto
	 *
	 * @param object $obj
	 */
	function auto_fields( $obj ) {
		$k = $this->_tbl_key;
		$properties = get_object_vars($obj);
		foreach ($properties as $name => $value) {
			if ($name[1] != '_' && $name != $k) {
				$this->fields[$name] = $value;
			}
		}
	}

	/**
	 * Agrega un campo
	 *
	 * @param string $field campo
	 * @param string $value valor
	 */
	function add_field( $field, $value ) {
		$this->fields[ $field ]	= $value;
	}

	/**
	 * Agrega varios campos
	 *
	 * @param array $fields campos
	 */
	function add_fields( $fields = array() ) {
		if ( is_array( $fields ) ) {
			$this->fields = $fields;
		}
	}

}