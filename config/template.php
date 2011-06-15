<?php
/**
 * Clase: Config_template
 * Clase de configuración de plantillas
 * @author DSD
 * @version 1.1.1
 */

class Config_template extends Config {
	/**
	 * Nombre de plantilla actual
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Constructor
	 *
	 */
	function __construct() {
		parent::get_config( 'template' );
	}
}

?>