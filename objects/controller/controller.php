<?php
/**
 * Controlador
 * @name Controller
 * @version 1.2
 */
abstract class Controller {
		
	/* Para utilizar la clase Controller, se debe crear una clase Controller_{algo}
	 * que contenga m�todos con la forma: execute_{algo}()
	 * el Framework har� la llamada en la forma $f->controller->{algo}()
	 */
	public function __call($task, $params) {
		/* verificar si tarea existe */
		$method = 'execute_' . $task;
		if (! method_exists($this, $method) ) { throw Application::Exception('Ctr003', array(get_class($this), $method)); }
		
		/* ejercer seguridad seg�n si sesi�n est� en modo estricto */
		
		/* soporte para debug */
		
		/* ejecutar tarea */
		//try {
			if (tracing()) Trace::info("Ejecutando la tarea " . $method, 'Controller');
			$this->$method();
		/*} catch(Exception $e) {
			echo("Error ejecutando controlador '" . $method . "': " . $e->getMessage());
		}*/
	}
}
?>