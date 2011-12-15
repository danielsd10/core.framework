<?php
/**
 * Controla la respuesta
 * @name Response
 * @version 1.2
 */
class Response {
	//private $buffer;
	
	public function __construct() {
		ob_clean();
		ob_start(array($this, "outputBuffer"));
	}
	
	public function outputBuffer($buffer) {
		return $buffer;
	}
	
	private function _print( $output ) {
		print $output;
	}
	
	public function view( $view_name, $data = null ) {
		if (tracing()) Trace::info("Cargando vista " . $view_name, 'Response');
		$application = Application::getInstance();
		$view_parts = explode("/", trim($view_name, "/"));
		$application->load->view( array(
			"config-path" => $application->config("application", "views-path"),
			"view-parts" => $view_parts,
			"view-data" => $data
		));
		//$view =& ob_get_contents();
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
	
	public function json( $output ) {
		$output = $this->encode($output);
		header('Content-Type: text/plain; charset=UTF-8');
		print json_encode($output);
	}
	
	public function file() {
		if ( file_exists($filename) ) {
			ob_clean();
			ob_start();
			@readfile($filename);
			$this->_output_buffer[] =& ob_get_clean();
		} else {
			return;
		}
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
	
	public function setMime() {
		
	}
	
	public function setCache() {
		
	}
	
	public function download( $filename ) {
		header('Content-Disposition: attachment; filename="' . $filename . '"');
	}
	
	public function redirect( $location ) {
		header("Location: " . $location);
	}
	
	public function forbidden( $message ) {
		header("HTTP/1.1 403 Forbidden");
		header("Location: " . Application::getInstance()->request->root);
		print $message;
	}
	
	public function header($name, $content) {
		header($name . ': ' . $content);
	}
	
	function __call($method, $args) {
		if ( $method == "print" ) {
			if ( count($args) > 1 ) { return false; }
			$this->_print($args[0]);
		}
	}
}
?>