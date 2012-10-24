<?php
class Response {
	
	/**
	 * envía un encabezado HTTP
	 * @param string $name nombre de encabezado
	 * @param string $content contenido de encabezado
	 */
	public function header($name, $content) {}
	
	/**
	 * carga una vista
	 * @param string $view_name ruta de la vista
	 * @param mixed $data datos para uso de la vista
	 */
	public function view($view_name, $data = null) {}
	
	/**
	 * envía una variable en formato JSON
	 * @param mixed $output variable a parsear
	 */
	public function json($output) {}
	
}
?>