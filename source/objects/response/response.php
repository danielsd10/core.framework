<?php
/**
 * Controla la respuesta
 * @name Response
 * @version 1.2
 */
class Response {
	private $buffer;
	public $sended = false;
	
	public function buffer($section_id) {
		return isset($this->buffer[$section_id]) ? $this->buffer[$section_id] : false;
	}
	
	public function module($module_type, $section_id, $data = null) {
		if (tracing()) Trace::info("Cargando módulo " . $module_type, 'Response');
		$module = new Module($module_type, $data);
		$this->buffer[$section_id][] = $module;
	}
	
	public function view($view_type, $data = null) {
		if (tracing()) Trace::info("Cargando vista " . $view_type, 'Response');
		$view = new View($view_type, $data);
		if ($this->sended) {
			echo $view;
		} else {
			$this->buffer['view'] = $view;
		}
	}
	
	public function template($template_name) {
		if (tracing()) Trace::info("Cargando plantilla " . $template_name, 'Response');
		$application = Application::getInstance();
		$view_parts = explode("/", trim($template_name, "/"));
		$data = $this->buffer;
		header('Content-Type: text/html; charset=UTF-8');
		$application->load->view( array(
			"config-path" => $application->config("application", "templates-path"),
			"view-parts" => $view_parts,
			"view-data" => $data
		));
		$this->sended = true;
	}
	
	public function json( $output ) {
		header('Content-Type: text/plain; charset=UTF-8');
		print json_encode($output);
		$this->sended = true;
	}
	
	public function file( $type, $data ) {
		header('Content-Type: ' . $type);
		print $data;
		$this->sended = true;
	}
	
	public function cookie($name, $value = null, $expire = 0) {
		switch (true) {
			case (is_null($value)):
				setcookie($name, null, time() - 1000);
				break;
			case ($expire != 0):
				setcookie($name, $value, time() + $expire);
				break;
			default:
				setcookie($name, $value);
		}
	}
	
	public function download( $filename, $type, $data ) {
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: ' . $type);
		print $data;
		$this->sended = true;
	}
	
	public function redirect( $location ) {
		header("Location: " . $location);
	}
	
	public function forbidden( $message ) {
		header("HTTP/1.1 403 Forbidden");
		//header("Location: " . Application::getInstance()->request->root);
		print $message;
	}
	
	public function error_internal( $message ) {
		header("HTTP/1.1 500 Internal Server Error");
		print $message;
	}
	
	public function error_user( $message ) {
		header("HTTP/1.1 501 Not Implemented");
		print $message;
	}
	
	// para errores definidos por el usuario
	public function _throw( $message, $parts = null ) {
		if (is_array($parts)) {
			$message = call_user_func_array('sprintf', array_merge($message, $parts));
		}
		throw Application::Exception('Usr000', array($message));
		//header("HTTP/1.1 501 Not Implemented");
		//print $message;
		//die();
	}
	
	public function header($name, $content) {
		header($name . ': ' . $content);
	}
	
	private function _print( $output ) {
		print $output;
	}
	
	private function encode($subject) {
		switch (true) {
		case (is_string($subject)):
			return utf8_encode($subject);
			break;
		case (is_array($subject)):
			foreach($subject as $k => $v) {
				$subject[$k] = $this->encode($v);
			}
			return $subject;
			break;
		case (is_object($subject)):
			foreach(get_object_vars($subject) as $k => $v) {
				$subject->$k = $this->encode($v);
			}
			return $subject;
			break;
		default:
			return $subject;
		}
	}
	
	function __call($method, $args) {
		switch ($method) {
			case "print":
				if ( count($args) > 1 ) { return false; }
				$this->_print($args[0]);
				break;
			case "throw":
				if ( count($args) > 1 ) { return false; }
				$this->_throw($args[0]);
				break;
		}
	}
}
?>