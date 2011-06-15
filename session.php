<?php
/**
 * @name Session
 * @package framework
 * Clase de control de sesiones
 * @author DSD
 * @version 1.1.1
 */

require_once( DIR_FRAMEWORK.DS."session".DS."user.php" );

class Session {
	/**
	 * identificador de sesi�n
	 *
	 * @var string
	 */
	public $id;

	/**
	 * informaci�n de usuario
	 *
	 * @var User
	 */
	public $user;

	/**
	 * variables registradas en la sesi�n
	 *
	 * @var array
	 */
	public $vars;

	/**
	 * constructor
	 *
	 */
	function __construct() {
		session_cache_limiter('none');
		session_start();
		$this->id = session_id();
	}

	/**
	 * finaliza la sesi�n
	 * redirige a la p�gina de inicio o login
	 *
	 */
	function end() {
		global $f;
		session_unset();
		session_destroy();

		header("HTTP/1.0 401 Unauthorized");
		header("Location: " . $f->config->url->base);
	}

	/**
	 * guarda la informaci�n de la sesi�n actual
	 *
	 */
	function save() {
		$_SESSION['framework.session'] = serialize( $this );
	}

	/**
	 * informa si la sesi�n es v�lida
	 * verifica si hay un usuario v�lido
	 *
	 * @return bool
	 */
	function is_valid() {
		if (! isset( $this->user ) ) { return false; }
		if (! $this->user instanceof User ) { return false; }
		return true;
	}

	/**
	 * agrega una variable a la sesi�n
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	function var_set( $key, $value ) {
		if (! is_array($this->vars) ) { $this->vars = array(); }
		$this->vars[$key] = $value;
	}

	/**
	 * elimina una variable de la sesi�n
	 *
	 * @param string $key
	 */
	function var_unset( $key ) {
		if ( isset( $this->vars[$key] ) ) {
			unset( $this->vars[$key] );
		}
	}

}

?>