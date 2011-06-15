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

	/**
	 * Carga la configuraci�n desde un archivo externo (*.cfg)
	 *
	 * @param Config_template $file nombre del archivo de configuraci�n
	 */
	function get_config($file) {
		$vars = parse_ini_file( "config/$file.cfg" );
		foreach ($vars as $k => $v) {
			$this->$k = $v;
		}
	}
}

?>