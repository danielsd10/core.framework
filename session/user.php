<?php
/**
 * @name User
 * @package framework.session
 * Sesiones: Informaci�n de usuario
 * @author DSD
 * @version 1.1.1
 */

class User {
	/**
	 * c�digo de usuario
	 *
	 * @var int
	 */
	public $id;
	/**
	 * nombre de inicio de sesi�n de usuario
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
	 * correo electr�nico
	 *
	 * @var string
	 */
	public $email;
	/**
	 * listado de m�dulos a los que el usuario tiene acceso
	 *
	 * @var array
	 */
	public $access;

	/**
	 * indica si el usuario tiene permiso de acceso al m�dulo especificado
	 *
	 * @param string $action m�dulo a verificar
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