<?php
/**
 * Clase cargadora de objetos del Framework
 * @name Loader
 * @version 1.2
 */
class Loader {
	
	public function datastore($params) {
		if (! isset($params["driver"]) ) { throw Application::Exception('Dst001'); }
		$filename = FrameworkPath.DS.'objects'.DS.'datastore'.DS.$params['driver'].DS.'datastore.php';
		if (! file_exists($filename)) { throw Application::Exception('Ldr001', array($filename)); }
		require_once ($filename);
		$ClassName = "Datastore_".$params["driver"];
		return new $ClassName($params);
	}
	
	public function controller($params) {
		$filename = isset($params['config-path']) ? $params['config-path'] : "controllers/$1";
		foreach ($params['vars'] as $i => $var) {
			$filename = str_replace("$".($i+1), $var, $filename);
		}
		if ( substr($filename, -4) != ".php" ) { $filename .= ".php"; }
		$filename = IndexPath.DS.$filename;
		if (! file_exists($filename)) { throw Application::Exception('Ldr001', array($filename)); }
		require_once ($filename);
		$ClassName = 'Controller_'.$params["name"];
		if (! class_exists($ClassName, false)) { throw Application::Exception('Ctr001', array($ClassName)); }
		$newObject = new $ClassName;
		if (! $newObject instanceof Controller) { throw Application::Exception('Ctr002', array($ClassName)); }
		if (tracing()) Trace::info("Controlador " . $ClassName . " cargado", 'Application');
		return $newObject;
	}
	
	public function model($params) {
		$ClassName = 'Model_'.$params["name"];
		if (! class_exists($ClassName, false)) {
			$filename = isset($params['config-path']) ? $params['config-path'] : "models/$1";
			foreach ($params['vars'] as $i => $var) {
				$filename = str_replace("$".($i+1), $var, $filename);
			}
			if ( substr($filename, -4) != ".php" ) { $filename .= ".php"; }
			$filename = IndexPath.DS.$filename;
			if (! file_exists($filename)) { throw Application::Exception('Ldr001', array($filename)); }
			require_once ($filename);
			if (! class_exists($ClassName, false)) { throw Application::Exception('Mod001', array($ClassName)); }
			
		}
		$newObject = new $ClassName;
		if (! $newObject instanceof Model) { throw Application::Exception('Mod002', array($ClassName)); }
		if (tracing()) Trace::info("Modelo " . $ClassName . " cargado", 'Application');
		return $newObject;
	}
	
	public function view($params) {
		$blocks = $params['view-parts'];
		$filename = isset($params['config-path']) ? $params['config-path'] : "views/$1";
		foreach ($blocks as $k => $block) {
			$filename = str_replace("$".($k+1), $block, $filename);
		}
		if ( substr($filename, -4) != ".php" ) { $filename .= ".php"; }
		$filename = IndexPath.DS.$filename;
		if (! file_exists($filename)) { throw Application::Exception('Ldr001', array($filename)); }
		if (is_array($params['view-data'])) {
			extract( $params['view-data'] );
		} elseif (is_object($params['view-data'])) {
			extract( get_object_vars($params['view-data']) );
		}
		include ($filename);
	}
	
	public function dataobject($params) {
		$ClassName = $params["name"];
		if (! class_exists($ClassName, false)) {
			$filename = isset($params['config-path']) ? $params['config-path'] : "models/dataobjects/$1";
			foreach ($params['vars'] as $i => $var) {
				$filename = str_replace("$".($i+1), $var, $filename);
			}
			if ( substr($filename, -4) != ".php" ) { $filename .= ".php"; }
			$filename = IndexPath.DS.$filename;
			if (! file_exists($filename)) { throw Application::Exception('Ldr001', array($filename)); }
			require_once ($filename);
			if (! class_exists($ClassName, false)) { throw Application::Exception('Dob001', array($ClassName)); }
		}
		return new $ClassName;
		$newObject = new $ClassName;
		if (! $newObject instanceof DataObject) { throw Application::Exception('Dob002', array($ClassName));}
		if (tracing()) Trace::info("Objeto " . $ClassName . " cargado", 'Application');
		return $newObject;
	}
	
	public function object($params) {
		if ($params['name'] =! "*") {
			if ( class_exists($params["name"], false) ) { return; }
		}
		$filename = isset($params['config-path']) ? $params['config-path'] : "models/dataobjects/$1";
		foreach ($params['vars'] as $i => $var) {
			$filename = str_replace("$".($i+1), $var, $filename);
		}
		if ( substr($filename, -4) != ".php" ) { $filename .= ".php"; }
		$filename = IndexPath.DS.$filename;
		if (! file_exists($filename)) { throw Application::Exception('Ldr001', array($filename)); }
		require_once ($filename);
		if (tracing()) Trace::info("Objetos del archivo " . $filename . " cargados", 'Application');
		if ($params['name'] =! "*") {
			if ( class_exists($params['name'], false) ) { throw Application::Exception('Dob003', array($params['name'])); }
		}
	}

	function _controller() {
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

	function _view($name, $data = null) {
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

	function _library($library_name) {
		if ( file_exists( DIR_FRAMEWORK.DS."libraries/{$library_name}.php" ) ) {
			require_once( DIR_FRAMEWORK.DS."libraries/{$library_name}.php" );
		} else if ( file_exists( DIR_LIBRARIES.DS."{$library_name}.php" ) ) {
			require_once( DIR_LIBRARIES.DS."{$library_name}.php" );
		}
	}

	function _database() {
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

	function _dataobject($name) {
		$pieces = explode("/", trim($name, "/"));
		$component = $pieces[0];
		$class = $pieces[1];

		if ( file_exists( DIR_COMPONENT.DS."{$component}/models/classes/{$class}.class.php" ) ) {
			require_once( DIR_COMPONENT.DS."{$component}/models/classes/{$class}.class.php" );
		}
	}

	function _session() {
		global $f;
		require_once( DIR_FRAMEWORK.DS.'session.php' );

		$f->session = new Session;

		if ( isset($_SESSION['framework.session']) ) {
			$f->session = unserialize( $_SESSION['framework.session'] );
		}
	}

}
?>