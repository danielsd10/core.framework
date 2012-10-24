<?php
class Application {
	public $request;
	public $response;
	public $session;
	public $datastore;
	public $controller;
	
	/**
	 * obtiene o establece una variable de configuración
	 * @param string $section sección
	 * @param string $key clave
	 * @param mixed $value valor
	 * @return mixed valor de clave
	 */
	public function config($section, $key = null, $value = null) {}
	
	/**
	 * autocarga de modelo
	 * @param string ruta del modelo
	 * @return Model nuevo modelo
	 */
	public function model($model_name) {}
	
	/**
	 * autocarga de objeto de datos
	 * @param string $object_name ruta y nombre del objeto
	 * @return DataObject nuevo objeto de datos
	 */
	public function dataobject($object_name) {}
	
	/**
	 * importar definiciones de otras clases
	 * @param mixed $classes clase(s) incluyendo su ruta
	 */
	public function uses($classes) {}
	
	/**
	 * inicia la ejecución de la aplicación
	 */
	public function run() {}
	
}
?>