<?php
class Statement {
	const sort_asc = 'asc';
	const sort_desc = 'desc';
	
	const limit_skip = 'skip';
	const limit_page = 'page';
	
	const join_left = 'left';
	const join_inner = 'inner';
	const join_right = 'right';
	
	const type_select = 'select';
	const type_insert = 'insert';
	const type_update = 'update';
	const type_delete = 'delete';
	const type_callsp = 'callsp';
	
	/**
	 * inicia una sentencia SELECT
	 * @param string $table_name nombre de la tabla
	 * @param array $fields campos o columnas
	 */
	public function select($table_name, $fields = null) {}
	
	/**
	 *
	 * inicia una sentencia INSERT
	 * @param string $table_name nombre de la tabla
	 * @param array $fields campos o columnas
	 */
	public function insert($table_name, $fields = array()) {}
	
	/**
	 *
	 * inicia una sentencia UPDATE
	 * @param string $table_name nombre de la tabla
	 * @param array $fields campos o columnas
	 */
	public function update($table_name, $fields = array()) {}
	
	/**
	 * inicia una sentencia DELETE
	 * @param string $table_name nombre de la tabla
	 */
	public function delete($table_name) {}
	
	/**
	 *
	 * inicia una llamada a procedimiento almacenado
	 * @param string $proc_name nombre del procedimiento
	 */
	public function call($proc_name) {}
	
	/**
	 * establece los parámetros de un procedimiento
	 * @param mixed $values
	 */
	public function params($values) {}
	
	/**
	 * aplica una expresión JOIN a la sentencia
	 * @param string $table_name nombre de tabla a anexar
	 * @param string $from_key campo clave de la tabla principal
	 * @param string $join_key campo clave de la tabla anexada
	 * @param mixed $type tipo de vinculación
	 */
	public function join($table_name, $from_key, $join_key, $type = self::join_left) {}
	
	/**
	 * aplica una expresión WHERE (condiciones) a la sentencia
	 * @param string $condition condición
	 */
	public function where($condition) {}
	
	/**
	 * establece los valores para la sentencia
	 * @param array $values valores
	 */
	public function values($values = array()) {}
	
	/**
	 * aplica una expresión ORDER BY a la sentencia
	 * @param string $field campo por el que se va a ordenar
	 * @param mixed $order modo en que se va ordenar
	 */
	public function sort($field, $order = self::sort_asc) {}
	
	/**
	 * aplica una expresión LIMIT a la sentencia
	 * @param int $count número de registros a retornar
	 * @param int $offset número de registros que no se incluirán
	 * @param mixed $type modo en que se va a limitar
	 */
	public function limit($count, $offset = null, $type = self::limit_skip) {}
	
	/**
	 * ejecuta la sentencia
	 * @return Dataset
	 */
	public function execute() {}
	
}
?>