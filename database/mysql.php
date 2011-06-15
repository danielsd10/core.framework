<?php
/**
 * @name Database_mysql
 * @package framework.database
 * Clase de configuración de rutas de URL
 * @author DSD
 * @version 1.1.1
 */

class Database_mysql extends Database {

	/**
	 * Nombre del servidor de base de datos
	 *
	 * @var string
	 */
	public $server = null;

	/**
	 * Usuario con acceso a la base de datos
	 *
	 * @var string
	 */
	public $user = null;

	/**
	 * Contraseña
	 *
	 * @var string
	 */
	public $password = null;

	/**
	 * Nombre de la base de datos
	 *
	 * @var string
	 */
	public $database = null;

	function __construct($config) {

		$this->server	= $config->server;
		$this->user		= $config->user;
		$this->password	= $config->password;
		$this->database	= $config->database;

		$this->connect();
	}

	/**
	 * Establece la conexión con la base de datos
	 *
	 */
	function connect() {
		$this->_cn = mysql_pconnect($this->server, $this->user, $this->password) or trigger_error(mysql_error(),E_USER_ERROR);
		mysql_select_db($this->database, $this->_cn);
		mysql_set_charset("ISO-8859-1");
	}

	/**
	 * Ejecutar consulta
	 *
	 * @param string $query consulta a ejecutar
	 * @return resource
	 */
	function execute($query) {
		$this->_sql = $query;
		// echo $this->_sql . "<br>"; // QUITAR ESTA LINEA ///////////////////////////////////
		if (! $this->_rs = mysql_query($this->_sql, $this->_cn)) { return false; } //trigger_error(mysql_error(), E_USER_ERROR);
		return $this->_rs;
	}

	/**
	 * Devuelve un registro del resultset activo
	 *
	 * @return object
	 */
	function get_record() {
		return mysql_fetch_object($this->_rs);
	}

	/**
	 * Devuelve el total de registros obtenidos en la última consulta
	 *
	 * @return int
	 */
	function total_records() {
		return mysql_affected_rows($this->_cn);
	}

	/**
	 * Devuelve el nuevo identificador o código obtenido en una sentencia Insert
	 *
	 * @return int
	 */
	function get_newid() {
		return mysql_insert_id( $this->_cn );
	}

	/**
	 * Devuelve un valor con escape para utilizarse en una consulta
	 *
	 * @param string $value valor a escapar
	 * @return string valor escapado
	 */
	function get_escaped( $value ) {
		$value = get_magic_quotes_gpc() ? stripslashes($value) : $value;
		$value = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($value) : mysql_escape_string($value);
		return $value;
	}

	/**
	 * Devuelve un valor con el respectivo encierre de comillas admitidas por la base de datos
	 *
	 * @param string $value
	 * @return string valor con comillas
	 */
	function get_quoted( $value ) {
		return '\''.$value.'\'';
	}

	/**
	 * Ejecuta una sentencia Insert
	 *
	 * @param string $table nombre de tabla
	 * @param mixed $fields objeto o array con campos y valores
	 */
	function execute_insert( $table, &$fields ) {
		$fmtsql = "INSERT INTO {$table} ( %s ) VALUES ( %s )";
		foreach ($fields as $f => $v) {
			if (is_array($v) || is_object($v) || $v === NULL) { continue; }
			if ($f[0] == '_') { continue; } // internal field
			$flds[] = $f;
			$vals[] = $v;
		}
		$query = sprintf( $fmtsql, implode( ",", $flds ), implode( ",", $vals ) ) ;
		if (! $this->execute($query) ) { return false; }
		return true;
	}

	/**
	 * Ejecuta una sentencia Update
	 *
	 * @param string $table nombre de tabla
	 * @param mixed $fields objeto o array con campos y valores
	 * @param mixed $params objeto o array con parametros (where...)
	 * @param bool $updateNulls indica si se actualizan campos vacíos como nulos
	 */
	function execute_update( $table, &$fields, $params, $updateNulls=true ) {
		$fmtsql = "UPDATE {$table} SET %s WHERE %s";
		foreach ($fields as $f => $v) {
			if (is_array($v) || is_object($v) || $v === NULL) { continue; }
			if ($f[0] == '_') { continue; } // internal field
			if ( $updateNulls && $v === null ) { $v = "NULL"; }
			$flds[] = $f . '=' . $v;
		}

		foreach ($params as $f => $v) {
			if (is_array($v) || is_object($v) || $v === NULL) { continue; }
			if ($f[0] == '_') { continue; } // internal field
			$pm_flds[] = $f . '=' . $v;
		}
		$query = sprintf( $fmtsql, implode( ",", $flds ) , implode( " AND ", $pm_flds ) ) ;
		if (! $this->execute($query) ) { return false; }
		return true;
	}

	/**
	 * Ejecuta una sentencia Delete
	 *
	 * @param string $table nombre de tabla
	 * @param mixed $fields objeto o array con campos y valores
	 * @param mixed $params objeto o array con parametros (where...)
	 */
	function execute_delete( $table, $params ) {
		$fmtsql = "DELETE FROM {$table} WHERE %s";
		foreach ($params as $f => $v) {
			if (is_array($v) || is_object($v) || $v === NULL) { continue; }
			if ($f[0] == '_') { continue; } // internal field
			$pm_flds[] = $f . '=' . $v;
		}
		$query = sprintf( $fmtsql, implode( " AND ", $pm_flds ) ) ;
		if (! $this->execute($query) ) { return false; }
		return true;
	}
}
?>