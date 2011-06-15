<?php
/**
 * @name Config
 * @package framework
 * Clase de configuraci�n de la aplicaci�n
 * @author DSD
 * @version 1.1.2
 */

class Config {
	/**
	 * Ruta de archivo de configuraci�n
	 *
	 * @var string
	 */
	private $_config_file;
	
	/**
	 * Configuraci�n de base de datos
	 *
	 * @var Config_database
	 */
	public $database;

	/**
	 * Configuraci�n de rutas de URL
	 *
	 * @var Config_url
	 */
	public $url;

	/**
	 * Configuraci�n de plantillas
	 *
	 * @var Config_template
	 */
	public $template;

	function __construct( $config_file ) {
		$this->_config_file = $config_file;
		$this->get_config();
	}
	
	/**
	 * Carga la configuraci�n desde un archivo externo
	 *
	 * @param Config_template $file nombre del archivo de configuraci�n
	 */
	function get_config() {
		$properties = get_object_vars($this);
		foreach ($properties as $property => $value) {
			if ($property{0} == "_") { continue; }
			$class_name = "Config_" . $property;
			$this->$property = new $class_name;
			$vars = parse_ini_file(  $this->_config_file, $property );
			foreach ($vars[$property] as $k => $v) {
				$this->$property->$k = $v;
			}
		}
	}
}

class Config_database {
	public $driver;
	public $server;
	public $port;
	public $user;
	public $password;
	public $database;
}

class Config_template {
	public $name;
}

class Config_url {
	public $dir;
	public $index;
	public $default;
	public $base;
	public $uri;
	public $query;
}
?>