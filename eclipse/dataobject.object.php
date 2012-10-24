<?php
class DataObject {
	
	/**
	 * Define la relaci�n entre el objeto y su fuenta de datos (tabla)
	 * @param string $table_name nombre de la tabla
	 * @param string $key campo clave o identificador
	 */
	public function define($table_name, $key) {}
	
	/**
	 * Obtiene los valores de la fuente de datos y los vincula al objeto
	 */
	public function load() {}
	
	/**
	 * Vincula valores al objeto
	 * @param mixed $from array u objeto con valores
	 * @param bool $apply_use indica si se descartar�n las propiedades no utilizadas
	 */
	public function bind($from, $apply_use) {}
	
	/**
	 * Almacena los valores del objeto en la fuente de datos, si no existe el registro es creado
	 * @return bool verdadero si se almacen� con �xito, falso en caso contrario
	 */
	public function store() {}
	
	/**
	 * Elimina el objeto de la fuente de datos
	 * @return bool verdadero si se elimin� con �xito, falso en caso contrario
	 */
	public function remove() {}
}
?>