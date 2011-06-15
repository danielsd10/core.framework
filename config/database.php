<?php
/**
 * Clase: Config_database
 * Clase de configuraci�n de base de datos
 * @author DSD
 * @version 1.1.2
 */

class Config_database extends Config {
	/**
	 * Driver de base de datos
	 *
	 * @var string
	 */
	public $driver;

	/**
	 * Nombre o IP del servidor
	 *
	 * @var string
	 */
	public $server;

	/**
	 * Puerto de acceso
	 *
	 * @var int
	 */
	public $port;

	/**
	 * Nombre de usuario
	 *
	 * @var string
	 */
	public $user;

	/**
	 * Contrase�a
	 *
	 * @var string
	 */
	public $password;

	/**
	 * Nombre de base de datos
	 *
	 * @var string
	 */
	public $database;

	/**
	 * Constructor
	 *
	 */
	function __construct() {
		parent::get_config( 'database' );
	}
}

?>