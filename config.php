<?php
/**
 * @name Config
 * @package framework
 * Clase de configuración de la aplicación
 * @author DSD
 * @version 1.1.2
 */

class Config {
	/**
	 * Configuración de base de datos
	 *
	 * @var Config_database
	 */
	public $database;

	/**
	 * Configuración de rutas de URL
	 *
	 * @var Config_url
	 */
	public $url;

	/**
	 * Configuración de plantillas
	 *
	 * @var Config_template
	 */
	public $template;

	/**
	 * Carga la configuración desde un archivo externo (*.cfg)
	 *
	 * @param Config_template $file nombre del archivo de configuración
	 */
	function get_config($file) {
		$vars = parse_ini_file( "config/$file.cfg" );
		foreach ($vars as $k => $v) {
			$this->$k = $v;
		}
	}
}

?>