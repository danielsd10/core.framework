<?php
/**
 * Clase de aplicación del framework
 * @name Application
 * @version 1.2
 */
final class Application {
	
	/**
	 * @var Loader
	 */
	public $load;
	/**
	 * @var Request
	 */
	public $request;
	/**
	 * @var Response
	 */
	public $response;
	/**
	 * @var Session
	 */
	public $session;
	/**
	 * @var Controller
	 */
	public $controller;
	/**
	 * @var Datastore
	 */
	public $datastore;
	
	const traceMode_off = 0;
	const traceMode_on = 1;
	
	const sessionMode_off = 0x0;
	const sessionMode_restrict = 0x1;
	const sessionMode_loose = 0x2;
	
	/**
	 * propiedades del objeto
	 * @var array
	 */
	private $properties;
	/**
	 * variables de configuración
	 * @var array
	 */
	private $config;
	/**
	 * contenido de las excepciones del framework
	 * @var string
	 */
	private static $exceptions = null;
	
	private static $instance;
	
	private function __construct() {
		$this->properties = array(
			'configFile' => null,
			'traceMode' => null,
			'sessionMode' => null
		);
		$this->config = array();
	}
	
	/**
	 * instancia de la aplicación
	 * @return Application
	 */
	public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Application();
        }
        return self::$instance;
    }
    
    public function setTraceMode($value) {
    	$this->properties['traceMode'] = $value;
		if ($this->properties['traceMode']) {
			$this->library('trace');
		} elseif (class_exists('Trace', false) ) {
			Trace::disable();
		}
    }
	
	public function config($section, $key = null, $value = null) {
		if (! isset($this->config[$section]) ) { throw Application::Exception('App003', array($section, $key)); }
		if ( is_null($key) ) { return $this->config[$section]; }
		if (! isset($this->config[$section][$key])) { $this->config[$section][$key] = ""; }
		if (! is_null($value)) {  $this->config[$section][$key] = $value; }
		return $this->config[$section][$key];
	}
	
	private function readConfigFile($filename) {
		if (! file_exists($filename) ) { throw Application::Exception('App002', array($filename)); }
		$this->properties['configFile'] = (strlen($this->properties['configFile']) == 0) ? $filename : $this->properties['configFile'] . ';' . $filename;
		
		if (tracing()) Trace::info("Cargando variables de configuracion del archivo " . $filename, 'Application');
		try {
			$config = parse_ini_file($filename, true);
			$this->config = array_merge($this->config, $config);
		} catch(Exception $e) {
			// archivo ini no válido
			throw Application::Exception('App012', array($e->getMessage()));
		}
		
		/* trace/debug mode */
		/*if ( isset($this->config['application']['trace-mode']) ) {
			switch ($this->config['application']['trace-mode']) {
				case "application":
					$this->properties['traceMode'] = self::traceMode_app;
					break;
				case "session":
					$this->properties['traceMode'] = self::traceMode_ses;
					break;
				case "off":
				default:
					$this->properties['traceMode'] = self::traceMode_off;
					break;
			}
		}*/
		
		/* session mode */
		/*if ( isset($this->config['application']['session-mode']) ) {
			switch ($this->config['application']['session-mode']) {
				case "application":
					$this->properties['sessionMode'] = self::sessionMode_restrict;
					break;
				case "session":
					$this->properties['sessionMode'] = self::sessionMode_loose;
					break;
				case "off":
				default:
					$this->properties['sessionMode'] = self::sessionMode_off;
					break;
			}
		}*/
		
	}
	
	public function run() {
		$this->load = new Loader;
		$this->request = new Request;
		$this->response = new Response;
		$this->session = new Session;
		
		$this->datastore = $this->load->datastore( $this->config['datastore'] );
		$this->datastore->connect();
		//$this->session->validate();
		$this->session->start();
		
		/*if ( preg_match('/\$[0-9]+/', $this->config['application']['controllers-path']) > 0 ) {
			preg_match_all('/\$([0-9]+)/', $this->config['application']['controllers-path'], $matches);
			$vars = $matches[1];
			foreach ($vars as $k => $v) {
				if ( $v != $k + 1 ) {
					throw new Exception();
				}
			}
		}*/
		$count_config_vars = preg_match_all('/\$[0-9]+/', $this->config['application']['controllers-path'], $matches);
		$call = strlen($this->request->call) != 0 ? $this->request->call : $this->config['application']['default-controller'];
		$call_parts = explode("/", trim($call, "/"));
		if ( count($call_parts) <= $count_config_vars ) {
			$task = "index";
			//$name = str_replace("/", "_", $this->request->call);
		} else {
			$task = array_pop($call_parts);
		}
		$this->controller = $this->load->controller( array(
			'config-path' => $this->config['application']['controllers-path'],
			'vars' => $call_parts,
			'name' => implode("_", $call_parts)
		));
		$this->controller->$task();
		ob_end_flush();
		//$this->response->output();
		//$this->session->save();
	}
	
	/**
	 * carga un modelo
	 * @param string $ModelName
	 * @return Model
	 */
	public function model($ModelName) {
		$vars = explode("/", trim($ModelName, "/"));
		$name = implode("_", $vars);
		return $this->load->model( array(
			'config-path' => $this->config['application']['models-path'],
			'vars' => $vars,
			'name' => $name
		));
	}
	
	/**
	 * carga un objeto de datos
	 * @param string $ObjectName
	 * @return DataObject
	 */
	public function dataobject($ObjectName) {
		$vars = explode("/", trim($ObjectName, "/"));
		$name = array_pop($vars);
		return $this->load->dataobject( array(
			'config-path' => $this->config['application']['dataobjects-path'],
			'vars' => $vars,
			'name' => $name
		));
	}
	
	public function uses($classes = array()) {
		if ( is_string($classes)) { $classes = array($classes); }
		foreach ($classes as $class) {
			$vars = explode("/", trim($class, "/"));
			$name = array_pop($vars);
			$this->load->object(array(
				'config-path' => $this->config['application']['dataobjects-path'],
				'vars' => $vars,
				'name' => $name
			));
		}
	}
	
	public function library($LibraryName) {
		$libpath = FrameworkPath.DS.'libraries/';
		switch ($LibraryName) {
			case 'trace':
				include_once( $libpath . 'trace/trace.php');
				return Trace::getInstance();
			case 'pdf':
			case 'webservice':
			case 'excel':
			default: throw Application::Exception('App004', array($LibraryName));
		}
	}
	
	public static function loadClass($ClassName) {
		$ClassFile = "";
		//echo "auloading..." . $ClassName . "<br>";
		if (tracing()) Trace::info("Cargando clase " . $ClassName, 'Application');
		switch ($ClassName) {
			/* clases base del framework */
			case 'Loader': $ClassFile = "objects/load/loader.php"; break;
			case 'Request': $ClassFile = "objects/request/request.php"; break;
			case 'Response': $ClassFile = "objects/response/response.php"; break;
			case 'Session': $ClassFile = "objects/session/session.php"; break;
			case 'Datastore': $ClassFile = "objects/datastore/datastore.php"; break;
			case 'Statement': $ClassFile = "objects/datastore/statement.php"; break;
			case 'Dataset': $ClassFile = "objects/datastore/dataset.php"; break;
			case 'sql': $ClassFile = "objects/datastore/mysql/sql.php"; break;
			
			case 'Controller': $ClassFile = "objects/controller/controller.php"; break;
			case 'Model': $ClassFile = "objects/controller/model.php"; break;
			case 'DataObject': $ClassFile = "objects/dataobject/object.php"; break;
			case 'DataObjectCollection': $ClassFile = "objects/dataobject/collection.php"; break;
			case 'DataObjectLink': $ClassFile = "objects/dataobject/link.php"; break;
			
			/* clases extendidas */
			case 'Datastore_mysql': $ClassFile = "objects/datastore/mysql/datastore.php"; break;
			
			/* clases de apoyo */
			
			/* interfaces */
			case 'iSQL': $ClassFile = "objects/datastore/interfaces/sql.php"; break;
			
			/* tipos de datos */
			case 'String': $ClassFile = "objects/dataobject/types/string.php"; break;
			case 'Number': $ClassFile = "objects/dataobject/types/number.php"; break;
			case 'Date': $ClassFile = "objects/dataobject/types/date.php"; break;
			case 'Binary': $ClassFile = "objects/dataobject/types/binary.php"; break;
			case 'File': $ClassFile = "objects/dataobject/types/file.php"; break;
			
			/* librerías */
			
			default:
				throw Application::Exception('App001', array($ClassName));
		}
		require_once( FrameworkPath.DS.$ClassFile );
	}
	
	public static function Exception($ErrID = null, $parts = array()) {
		
		if (is_null(self::$exceptions)) {
			self::$exceptions = file_get_contents(FrameworkPath.DS.'exceptions.inc');
		}
		$count = preg_match('/' . $ErrID . '.+/', self::$exceptions, $match);
		$line = $count>0 ? $match[0] : null;
		
		if (is_null($line)) {
			// error desconocido
			$count = preg_match('App255.+/', self::$exceptions, $match);
			$line = $count>0 ? $match[0] : null;
			$err = explode("\t", $line);
			return new Exception($err[2]);
		}
		
		$err = explode("\t", $line);
		if (count($parts)) {
			$params = array_merge(array($err[2]), $parts);
			$err[2] = call_user_func_array('sprintf', $params);
		}
		
		return new Exception($err[2]);
	}
	
	/*public static function RegisterExceptions($exceptions = array()) {
		array_merge(self::$instance->exceptions, $exceptions);
	}*/
	
	public static function handleException(Exception $exception) {
		// interfaz común de salida de error
		
		// debug
		if (tracing()) Trace::error($exception->getMessage());
		//trigger_error($exception->getMessage(), E_USER_ERROR);
	}
	
	public static function handleError($errno, $errstr) {
		if ( $errno == E_USER_ERROR ) {
		echo $errno . ": " . $errstr . "<br>";
		die();
		}
		
	}
	
	public static function log() {
		
	}
	
	public static function trace($subject) {
		
	}
	
	public function __get($property) {
		if ( array_key_exists($property, $this->properties) ) {
			return $this->properties[$property];
		} else {
			throw Application::Exception('App005', array($property));
		}
	}
	
	public function __set($property, $value) {
		switch ($property) {
			case "configFile": $this->readConfigFile($value); break;
			case "traceMode": $this->setTraceMode($value); break;
			case "sessionMode": $this->properties['sessionMode'] = $value; break;
			default:
				throw Application::Exception('App005', array($property));
		}
	}
	
	private function __clone() {
		/* para evitar que se haga otra instancia de Application utilizando clone. */
	}
	
	public function __call($function, $params) {
		switch ($function) {
			case 'use':
				$this->uses($params[0]);
				break;
		}
	}
}
?>