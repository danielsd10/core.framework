<?php
/**
 * Clase para manejar vistas de aplicaciones
 * @name View
 * @version 0.1
 */
class View {
	public $type;
	public $content;
	private $params;
	
	public function __construct($type, $data) {
		$this->type = $type;
		$this->params = $data;
		$this->exec();
	}
	
	protected function exec() {
		$application = Application::getInstance();
		$view_parts = explode("/", trim($this->type, "/"));
		ob_clean();
		ob_start();
		$application->load->view( array(
			"config-path" => $application->config("application", "views-path"),
			"view-parts" => $view_parts,
			"view-data" => array('v' => $this)
		));
		$this->content = ob_get_clean();
	}
	
	public function __get($property) {
		return array_key_exists($property, $this->params) ? $this->params[$property] : null;
	}
	
	public function __set($property, $value) {
		if (array_key_exists($property, $this->params)) {
			$this->params[$property] = $value;
		}
	}
	
	public function __isset($property) {
		return isset($this->params[$property]);
	}
	
	public function __toString() {
		return $this->content;
	}
}
?>