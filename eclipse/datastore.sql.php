<?php
abstract class sql {
	/**
	 * prepara los valores para que puedan ser enviados en una sentencia SQL
	 * @param mixed $value valor a tratar
	 * @return mixed valor tratado
	 */
	static public function parse($value) {}

	/**
	 *
	 * sentencia SELECT
	 * @param string $table_name tabla
	 * @param array $fields campos
	 * @param array $join sentencias join
	 * @param array $where condiciones where
	 * @param array $sort
	 * @param unknown_type $limit
	 * @return string sentencia sql
	 */
	static public function sel($table_name, $fields = array(), $join = array(), $where = array(), $sort = array(), $limit = array()) {}
	
	/**
	 *
	 * sentencia INSERT
	 * @param string $table_name tabla
	 * @param array $fields campos
	 * @param array $values valores
	 * @return string sentencia sql
	 */
	static public function ins($table_name, $fields = array(), $values = array()) {}
	
	/**
	 * sentencia UPDATE
	 * @param string $table_name tabla
	 * @param array $fields campos
	 * @param array $values valores
	 * @return string sentencia sql
	 */
	static public function upd($table_name, $fields = array(), $values = array()) {}
	
	/**
	 * sentencia DELETE
	 * @param string $table_name tabla
	 * @param array $where condiciones
	 * @return string sentencia sql
	 */
	static public function del($table_name, $where = array()) {}
	
	/**
	 * llamada a procedimiento almacenado
	 */
	static public function sp() {}
	
	/**
	 * incluye una función según el motor de base de datos
	 * @param string $function_name función o procedimiento
	 * @param array $params parámetros
	 * @return string sentencia sql
	 */
	static public function fn($function_name, $params) {}
	
	/**
	 * condición "igual a": campo = valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function eq($field, $value) {}
	
	/**
	 * condición "no es igual a": campo != valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function neq($field, $value) {}
	
	/**
	 * condición "igual a múltiples valores": campo IN (valor1, ..., valorn)
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function in($field, $values) {}
	
	/**
	 * condición "no es igual a múltiples valores": campo NOT IN (valor1, ..., valorn)
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function nin($field, $values) {}
	
	/**
	 * condición "similar a": campo like %valor%
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function like($field, $value) {}
	
	/**
	 * condición "empieza por": campo like valor%
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function llike($field, $value) {}
	
	/**
	 * condición "termina en": campo like %valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function rlike($field, $value) {}
	
	/**
	 * condición "entre 2 valores": campo between valor1 and valor2
	 * @param string $field campo
	 * @param mixed $lower valor mínimo
	 * @param mixed $higher valor máximo
	 * @return string expresión sql
	 */
	static public function btw($field, $lower, $higher) {}
	
	/**
	 * condición "menor que": campo < valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function lt($field, $value) {}
	
	/**
	 * condición "menor o igual a": campo <= valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function elt($field, $value) {}
	
	/**
	 * condición "mayor que": campo > valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function gt($field, $value) {}
	
	/**
	 * condición "mayor o igual a": campo >= valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function egt($field, $value) {}
	
	/**
	 * condición con subconsulta: campo IN (subconsulta)
	 * @param string $field campo
	 * @param string $subquery subconsulta
	 * @return string expresión sql
	 */
	static public function sub($field, $subquery) {}
	
	/**
	 * condición de búsqueda por texto
	 * @param mixed $field campo(s)
	 * @param mixed $value valor
	 * @return string expresión sql
	 */
	static public function src($fields, $value) {}
	
	/**
	 * condición de búsqueda por expresión regular
	 * @param string $field campo
	 * @param string $regexp expresión regular
	 * @return string expresión sql
	 */
	static public function regexp($field, $regexp) {}
	
	/**
	 * operador de bits AND: "campo & valor"
	 * @param string $field campo
	 * @param mixed $value valor
	 */
	static public function bwand($field, $value) {}

	/**
	 * operador de bits OR: "campo | valor"
	 * @param string $field campo
	 * @param mixed $value valor
	 */
	static public function bwor($field, $value) {}
	
	/**
	 * operador de bits XOR: "campo ^ valor"
	 * @param string $field campo
	 * @param mixed $value valor
	 */
	static public function bwxor($field, $value) {}
	
	/**
	 * operador de bits NOT: "campo & ~valor"
	 * @param string $field campo
	 * @param mixed $value valor
	 */
	static public function bwnot($field, $value) {}
}
?>