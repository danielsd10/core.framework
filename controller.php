<?php
/**
 * @name Controller
 * @package framework
 * MVC: Controlador de ejecución
 * @author DSD
 * @version 1.1.1
 */

class Controller {
	/**
	 * Componente
	 *
	 * @var string
	 */
	public $component;

	/**
	 * Módulo
	 *
	 * @var string
	 */
	public $module;

	/**
	 * Tarea
	 *
	 * @var string
	 */
	public $task;

	/**
	 * Objeto cargador
	 *
	 * @var Loader
	 */
	public $load;

	/**
	 * Objeto de datos
	 *
	 * @var unknown_type
	 */
	public $data;

	/**
	 * Modelo en ejecución
	 *
	 * @var Model
	 */
	public $model;

	/**
	 * Conjunto de vistas cargadas
	 *
	 * @var array
	 */
	public $views;

	function __construct( $component, $module, $task="" ) {
		$this->component = $component;
		$this->module = $module;
		$this->task = $task;
		$this->data = array();
		$this->views = array();
		$this->load = new Loader;
	}

	function execute() {
		if (strlen($this->task) > 0) {
			$execute = 'execute_' . $this->task;
		} else {
			$execute = 'execute_index';
		}

		if ( method_exists($this, $execute) ) {
			$this->$execute();
		} else {
			echo ('error: funci&oacute;n de ejecuci&oacute;n no existe');
		}
	}

	function output() {
		foreach ( $this->views as $view ) {
			echo ( $view );
		}
		return true;
	}

}

?>