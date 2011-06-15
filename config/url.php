<?php
/**
 * Clase: Config_url
 * Clase de configuraci�n de rutas de URL
 * @author DSD
 * @version 1.1.1
 */

class Config_url extends Config {
	/**
	 * Directorio de trabajo
	 *
	 * @var string
	 */
	public $dir;

	/**
	 * Archivo inicial (index.php)
	 *
	 * @var string
	 */
	public $index;

	/**
	 * Componente por defecto
	 *
	 * @var string
	 */
	public $default;

	/**
	 * Ruta base de la aplicaci�n
	 *
	 * @var string
	 */
	public $base;

	/**
	 * Constructor
	 *
	 */
	function __construct() {
		parent::get_config( 'url' );
	}
}

?>