<?php
/**
 * @name Loader
 * @package framework
 * Cargador de objetos del framework
 * @author DSD
 * @version 1.1.2
 */

class Loader {

	function config() {
		global $f;
		$config_file = DIR_BASE.DS."lite.framework.config";
		$f->config = new Config( $config_file );
	}

	function controller() {
		global $f;
		require_once( DIR_FRAMEWORK.DS.'controller.php' );
		require_once( DIR_FRAMEWORK.DS.'model.php' );
		require_once( DIR_FRAMEWORK.DS.'table.php' );
		require_once( DIR_FRAMEWORK.DS.'procedure.php' );
		require_once( DIR_FRAMEWORK.DS.'dataobject.php' );

		//$f->config->template = new Config;
		//$f->config->template->get_config( 'template' );

		// cargar variables GET y POST
		$f->request = $_REQUEST;
		// cargar archivos
		$f->uploads = $_FILES;

		//$f->config->url = new Config;
		//$f->config->url->get_config( 'url' );
		$f->config->url->uri = $_SERVER['REQUEST_URI'];
		$f->config->url->query = $_SERVER['QUERY_STRING'];
		$f->config->url->base = "http://" . $_SERVER['HTTP_HOST'] . "/" . $f->config->url->dir;

		$path = trim($f->config->url->uri, "/");
		$path = str_replace( $f->config->url->dir, "", $path );
		$path = str_replace( $f->config->url->index, "", $path );
		$path = str_replace( $f->config->url->query, "", $path );
		$path = trim( trim( $path, "?"), "/");

		$path = (strlen($path) > 0) ? $path : $f->config->url->default;
		$path = explode("/", $path);

		if (! is_array( $path )) { /*mostrar error */ die("url invalido"); }
		if ( count( $path ) < 2 ) { /*mostrar error */ die("url invalido"); }

		$component = $path[0];
		$module = $path[1];
		$task = isset( $path[2] ) ? $path[2] : "index";

		$cont_class = "Controller";
		if ( file_exists( DIR_COMPONENT.DS."{$component}/controllers/{$module}.php" ) ) {
			require_once( DIR_COMPONENT.DS."{$component}/controllers/{$module}.php" );
			$cont_class = "Controller_{$component}_{$module}";
			$f->controller = new $cont_class( $component, $module, $task );
		} else {
			die("componente invalido");
		}
	}

	function model($name) {
		global $f;
		$pieces = explode("/", trim($name, "/"));
		$component = $pieces[0];
		$module = $pieces[1];
		if ( file_exists( DIR_COMPONENT.DS."{$component}/models/{$module}.php" ) ) {
			require_once( DIR_COMPONENT.DS."{$component}/models/{$module}.php" );
			//$model_class = "Model_{$component}_{$module}";
			//$f->controller->model = new $model_class( $f->database );
		} else {
			echo ("error: modelo invalido");
		}
	}

	function view($name, $data = null) {
		global $f;
		$pieces = explode("/", trim($name, "/"));
		$component = $pieces[0];
		$module = $pieces[1];
		if ( file_exists( DIR_COMPONENT.DS."{$component}/views/{$module}.php" ) ) {
			ob_clean();
			ob_start();
			if ($data) extract( $data );
			include( DIR_COMPONENT.DS."{$component}/views/{$module}.php" );
			$view =& ob_get_clean();
			array_push( $f->controller->views, $view);
		} else {
			echo ("error: vista invalida");
		}
	}

	function library($library_name) {
		if ( file_exists( DIR_FRAMEWORK.DS."libraries/{$library_name}.php" ) ) {
			require_once( DIR_FRAMEWORK.DS."libraries/{$library_name}.php" );
		} else if ( file_exists( DIR_LIBRARIES.DS."{$library_name}.php" ) ) {
			require_once( DIR_LIBRARIES.DS."{$library_name}.php" );
		}
	}

	function database() {
		global $f;
		//$this->config->database = new Config;
		//this->config->database->get_config( 'database' );

		require_once( DIR_FRAMEWORK.DS.'database.php' );
		$db_class = "Database";
		if ( $f->config->database->driver ) {
			if ( file_exists( DIR_FRAMEWORK.DS."database/{$f->config->database->driver}.php" ) ) {
				require_once( DIR_FRAMEWORK.DS."database/{$f->config->database->driver}.php" );
				$db_class = "Database_{$f->config->database->driver}";
			}
		}
		$f->database = new $db_class($f->config->database);
	}

	function dataobject($name) {
		$pieces = explode("/", trim($name, "/"));
		$component = $pieces[0];
		$class = $pieces[1];

		if ( file_exists( DIR_COMPONENT.DS."{$component}/models/classes/{$class}.class.php" ) ) {
			require_once( DIR_COMPONENT.DS."{$component}/models/classes/{$class}.class.php" );
		}
	}

	function session() {
		global $f;
		require_once( DIR_FRAMEWORK.DS.'session.php' );

		$f->session = new Session;

		if ( isset($_SESSION['framework.session']) ) {
			$f->session = unserialize( $_SESSION['framework.session'] );
		}
	}

}

?>