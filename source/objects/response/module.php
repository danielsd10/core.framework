<?php
/**
 * Clase para manejar módulos
 * @name Module
 * @version 0.1
 */
class Module {
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
			"config-path" => $application->config("application", "modules-path"),
			"view-parts" => $view_parts,
			"view-data" => array('m' => $this)
		));
		$this->content = ob_get_clean();
	}
	
	public function __get($property) {
		return array_key_exists($property, $this->params) ? $this->params[$property] : null;
	}
	
	public function __toString() {
		return $this->content;
	}
}
?>