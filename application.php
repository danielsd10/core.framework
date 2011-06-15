<?php
/**
 * @name Aplication
 * @package framework
 * Clase de aplicación del framework
 * @author DSD
 * @version 1.1.2
 */
require_once( DIR_FRAMEWORK.DS.'config.php' );
require_once( DIR_FRAMEWORK.DS.'loader.php' );

class Application {
	/**
	 * objeto cargador de sub objetos
	 *
	 * @var Loader
	 */
	public $load;

	/**
	 * parámetros de configuración
	 *
	 * @var Config
	 */
	public $config;

	/**
	 * acceso a base de datos
	 *
	 * @var Database
	 */
	public $database;

	/**
	 * información de sesión
	 *
	 * @var Session
	 */
	public $session;

	/**
	 * controlador en ejecución actual
	 *
	 * @var Controller
	 */
	public $controller;

	/**
	 * array con variables de entorno (GET, POST, COOKIES)
	 *
	 * @var Array
	 */
	public $request;

	/**
	 * array con información de archivos cargados mediante POST al servidor
	 *
	 * @var Array
	 */
	public $uploads;

	/**
	 * Constructor
	 *
	 */
	function __construct() {

		$this->load = new Loader;
	}

	function set($property, $obj = null) {
		$this->$property = $obj;
	}

	function output() {

	}

	function &get_instance() {
		global $f;

		if (is_object($f)) {
			return $f;
		}
	}

	function redirect($to) {
		header("Location: " . $to);
	}
}

?>