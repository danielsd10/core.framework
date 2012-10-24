<?php
class Model {
	/**
	 * información de paginación
	 * @var Paging
	 */
	public $paging;
	
	/**
	 * parámetros de uso del modelo
	 * @var array
	 */
	public $params;
	
	/**
	 * establece la paginación del modelo
	 * @param int $page página
	 * @param int $items_page items por página
	 * @param int $total_items total de items encontrados
	 * @return Model
	 */
	public function paging($page, $items_page, $total_items) {}
	
	/**
	 * establece uno o varios parámetros
	 * @param string $key clave
	 * @param string $value valor
	 * @return Model
	 */
	public function param($key, $value) {}
	
	/**
	 * establece uno o varios parámetros
	 * @param mixed $params parametros a enviar
	 * @return Model
	 */
	public function params($params) {}
	
	/**
	 * permite crear un nuevo objeto
	 * @param string $name sufijo de función
	 * @return Model
	 */
	public function create($name) {}
	
	/**
	 * obtiene los objetos almacenados
	 * @param string $name sufijo de función
	 * @return Model
	 */
	public function get($name) {}
	
	/**
	 * guarda cambios en los objetos obtenidos
	 * @param string $name sufijo de función
	 * @return Model
	 */
	public function save($name) {}
	
	/**
	 * elimina los objetos obtenidos
	 * @param string $name sufijo de función
	 * @return Model
	 */
	public function delete($name) {}
}
?>