<?php
/**
 * @name Database
 * @package framework
 * Clase de controlador de base de datos
 * @author DSD
 * @version 1.1.4
 */

class Database {
	/**
	 * Identificador de conexión (connection)
	 *
	 * @var resource
	 */
	public $_cn = null;

	/**
	 * Identificador de juego de resultados (resulset)
	 *
	 * @var resource
	 */
	public $_rs = null;

	/**
	 * Consulta SQL a ejecutar
	 *
	 * @var string
	 */
	public $_sql = null;

	/**
	 * Establecer consulta
	 *
	 * @param string $sql
	 */
	function set_query( $sql ) {
		$this->_sql	= $sql;
	}

	/**
	 * Obtener consulta
	 *
	 * @return string
	 */
	function get_query() {
		return $this->_sql;
	}

	/**
	 * Ejecutar consulta
	 *
	 * @param string $query consulta a ejecutar
	 * @return resource
	 */
	function execute($query) {}

	/**
	 * Ejecuta una sentencia Insert
	 *
	 * @param string $table nombre de tabla
	 * @param mixed $fields objeto o array con campos y valores
	 */
	function execute_insert( $table, &$fields ) {}

	/**
	 * Ejecuta una sentencia Update
	 *
	 * @param string $table nombre de tabla
	 * @param mixed $fields objeto o array con campos y valores
	 * @param mixed $params objeto o array con parametros (where...)
	 * @param bool $updateNulls indica si se actualizan campos vacíos como nulos
	 */
	function execute_update( $table, &$fields, $params, $updateNulls=true ) {}

	/**
	 * Ejecuta una sentencia Delete
	 *
	 * @param string $table nombre de tabla
	 * @param mixed $fields objeto o array con campos y valores
	 * @param mixed $params objeto o array con parametros (where...)
	 */
	function execute_delete( $table, $params ) {}

	/**
	 * Devuelve un valor con escape para utilizarse en una consulta
	 *
	 * @param string $value valor a escapar
	 * @return string valor escapado
	 */
	function get_escaped( $value ) {}

	/**
	 * Devuelve un valor con el respectivo encierre de comillas admitidas por la base de datos
	 *
	 * @param string $value
	 * @return string valor con comillas
	 */
	function get_quoted( $value ) {}

	/**
	 * Devuelve un registro del resultset activo
	 *
	 * @return object
	 */
	function get_record() {}

	/**
	 * Devuelve el total de registros obtenidos en la última consulta
	 *
	 * @return int
	 */
	function total_records() {}

	/**
	 * Devuelve el nuevo identificador o código obtenido en una sentencia Insert
	 *
	 * @return int
	 */
	function get_newid() {}

}

?>