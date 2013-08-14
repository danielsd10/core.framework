<?php
/**
 * Controlador
 * @name Controller
 * @version 1.2
 */
abstract class Controller {
	
	/*
	 * variación de ejecución de objeto / componente para sitio web
	 * cargará el archivo relacionado al objeto y ejecutará la función solicitada
	 */
	public function execute_object($object, $action) {
		$Application = Application::getInstance();
		$Application->object($object);
		$vars = explode("/", trim($object, "/"));
		$name = ucfirst(array_pop($vars));
		$object = new $name;
		if (! method_exists($object, $action) ) { throw Application::Exception('Ctr003', array(get_class($this), $method)); }
		$object->$action();
	}
	
	/* Para utilizar la clase Controller, se debe crear una clase Controller_{algo}
	 * que contenga métodos con la forma: execute_{algo}()
	 * el Framework hará la llamada en la forma $f->controller->{algo}()
	 */
	public function __call($task, $params) {
		/* verificar si tarea existe */
		$method = 'execute_' . $task;
		if (! method_exists($this, $method) ) { throw Application::Exception('Ctr003', array(get_class($this), $method)); }
		
		/* ejercer seguridad según si sesión está en modo estricto */
		/*$Application = Application::getInstance;
		if (! $Application->session->valid) {
			$Application->response->forbidden();
		}*/
		
		/* ejecutar tarea */
		//try {
			/* soporte para debug */
			if (tracing()) Trace::info("Ejecutando la tarea " . $method, 'Controller');
			$this->$method();
		/*} catch(Exception $e) {
			echo("Error ejecutando controlador '" . $method . "': " . $e->getMessage());
		}*/
	}
}
?>