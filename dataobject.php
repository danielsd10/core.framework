<?php
/**
 * @name DataObject
 * @package framework
 * Clase de manejo de datos
 * @author DSD
 * @version 1.1.2
 */

require_once( DIR_FRAMEWORK.DS."dataobjects".DS."string.php" );
require_once( DIR_FRAMEWORK.DS."dataobjects".DS."number.php" );
require_once( DIR_FRAMEWORK.DS."dataobjects".DS."date.php" );
require_once( DIR_FRAMEWORK.DS."dataobjects".DS."file.php" );
require_once( DIR_FRAMEWORK.DS."dataobjects".DS."enum.php" );
require_once( DIR_FRAMEWORK.DS."dataobjects".DS."collection.php" );
require_once( DIR_FRAMEWORK.DS."dataobjects".DS."binary.php" );

class DataObject {
	/**
	 * indica si el objeto está registrado en la base de datos
	 * si es el caso, en una operación store se procede a crearlo,
	 * de lo contrario solo modificarlo
	 *
	 * @var bool
	 */
	public $_is_new = false;

	/**
	 * Carga el objeto desde una base de datos
	 *
	 * @param string $from nombre de tabla
	 * @param string $key nombre de campo clave (pk)
	 * @param mixed $value valor del campo clave
	 * @param bool $autoBind indica si resultset se vincula automáticamente o se devuelve para su vinculación manual
	 * @return object resultset con los datos obtenidos
	 */
	function load( $from, $key, $value, $autoBind = true ) {
		global $f;
		$sql = "SELECT * FROM {$from} WHERE {$key} = {$value}";

		$rs = $f->database->execute( $sql );
		if ($rs === false) { $this->_is_new = true; return false; }
		elseif ( $f->database->total_records() == 0 ) { $this->_is_new = true; return false; }
		elseif ( ! $autoBind ) { $this->_is_new = false; return $f->database->get_record(); }
		else {
			$record = $f->database->get_record();
			$properties = get_object_vars( $this );
			foreach ($record as $k => $v) {
				if (! property_exists($this, $k) ) { continue; }
				if ( $this->$k instanceof String || $this->$k instanceof Number || $this->$k instanceof Date || $this->$k instanceof Enum || $this->$k instanceof Binary ) {
					$this->$k->set( $v );
				} elseif ( $this->$k instanceof File ) {
					$this->$k->link( $v );
				}
			}
			$this->_is_new = false;
			return $record;
		}
	}

	/**
	 * Vincula los resultados de una consulta al objeto
	 *
	 * @param mixed $from objeto o array con campos y valores
	 * @param mixed $ignore array o string con valores a ignorar
	 * @return bool retorna true si se vinculo con éxito
	 */
	function bind( $from, $ignore = array() ) {
		$fromArray	= is_array( $from );
		$fromObject	= is_object( $from );

		if (!$fromArray && !$fromObject) { return false; }

		if (!is_array( $ignore )) { $ignore = explode( ' ', $ignore ); }

		if ($fromObject) {
			$vars = get_object_vars($from);
		} else {
			$vars = $from;
		}

		foreach ($vars as $k => $v) {
			if (!in_array( $k, $ignore )) {
				if ( $fromArray && property_exists($this, $k) && isset($from[$k]) ) {
					$value = (strlen($from[$k]) > 0) ? $from[$k] : null;
				} else if ( $fromObject && property_exists($this, $k) && isset($from->$k) ) {
					$value = (strlen($from->$k) > 0) ? $from->$k : null;
				} else { continue; }

				if ( $this->$k instanceof String || $this->$k instanceof Number || $this->$k instanceof Date || $this->$k instanceof Enum || $this->$k instanceof Binary ) {
					$this->$k->set( $value );
				} elseif ( $this->$k instanceof File ) {
					$this->$k->link( $value );
				}
			}
		}
		return true;
	}

	/**
	 * Guarda la información del objeto en la base de datos
	 *
	 * @param Table $table tabla con los valores a enviar
	 * @param string $keyName nombre del campo clave (pk)
	 * @return bool devuelve verdadero si se guardó con éxito
	 */
	function store( &$table, $keyName = null ) {
		global $f;
		if ( $this->_is_new ) {
			if (! $f->database->execute_insert( $table->name, $table->fields ) ) { return false; }
			if ( $keyName ) {
				$this->$keyName->set( $f->database->get_newid() );
			}
		} else {
			if (! $f->database->execute_update( $table->name, $table->fields, $table->keys ) ) { return false; }
		}
		return true;
	}

	/**
	 * Elimina el objeto actual de la base de datos
	 *
	 * @param Table $table objeto con parámetros a eliminar
	 * @return bool devuelve verdadero si se eliminó
	 */
	function remove( &$table ) {
		global $f;
		if (! $f->database->execute_delete( $table->name, $table->keys ) ) { return false; }
		return true;
	}

	/**
	 * @deprecated utilizar bind_subobject
	 */
	function build_subobject( $object, $param ) {
		$obj = new stdClass;
		foreach ( $object as $field => $val ) {
			if ( strpos( $field, $param ) !== false ) {
				$field_name = str_replace( "{$param}_", "", $field );
				$obj->$field_name = $val;
			}
		}
		return $obj;
	}

	/**
	 * Vincula los resultados de una consulta a un subobjeto
	 *
	 * @param mixed $from objeto o array con campos y valores
	 * @param string $prefix prefijo para determinar qué campos extrar del objeto
	 * @return mixed devuelve un nuevo objeto que puede luego ser vinculado a otro
	 */
	function bind_subobject( $from, $prefix ) {
		$obj = new stdClass;
		foreach ( $from as $field => $val ) {
			if ( strpos( $field, $prefix ) !== false ) {
				$field_name = str_replace( "{$prefix}_", "", $field );
				$obj->$field_name = $val;
			}
		}
		return $obj;
	}

}
?>