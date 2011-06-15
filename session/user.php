<?php
/**
 * @name User
 * @package framework.session
 * Sesiones: Información de usuario
 * @author DSD
 * @version 1.1.1
 */

class User {
	/**
	 * código de usuario
	 *
	 * @var int
	 */
	public $id;
	/**
	 * nombre de inicio de sesión de usuario
	 *
	 * @var string
	 */
	public $name;
	/**
	 * nombre completo del usuario
	 *
	 * @var string
	 */
	public $fullname;
	/**
	 * correo electrónico
	 *
	 * @var string
	 */
	public $email;
	/**
	 * listado de módulos a los que el usuario tiene acceso
	 *
	 * @var array
	 */
	public $access;

	/**
	 * indica si el usuario tiene permiso de acceso al módulo especificado
	 *
	 * @param string $action módulo a verificar
	 */
	function allow_to( $action ) {
		if (! is_array($this->access) ) { return false; }

		$levels = substr_count($action, "/");

		if ( $levels == 0 ) {
			foreach ($this->access as $key) {
				if ( substr($key,0,2) == $action ) { return true; }
			}
			return false;
		} else {
			$k = array_search( $action, $this->access );
			return ($k === false) ? false : true;
		}
	}
}

?>