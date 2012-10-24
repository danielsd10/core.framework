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
	 * incluye una funci�n seg�n el motor de base de datos
	 * @param string $function_name funci�n o procedimiento
	 * @param array $params par�metros
	 * @return string sentencia sql
	 */
	static public function fn($function_name, $params) {}
	
	/**
	 * condici�n "igual a": campo = valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function eq($field, $value) {}
	
	/**
	 * condici�n "no es igual a": campo != valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function neq($field, $value) {}
	
	/**
	 * condici�n "igual a m�ltiples valores": campo IN (valor1, ..., valorn)
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function in($field, $values) {}
	
	/**
	 * condici�n "no es igual a m�ltiples valores": campo NOT IN (valor1, ..., valorn)
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function nin($field, $values) {}
	
	/**
	 * condici�n "similar a": campo like %valor%
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function like($field, $value) {}
	
	/**
	 * condici�n "empieza por": campo like valor%
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function llike($field, $value) {}
	
	/**
	 * condici�n "termina en": campo like %valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function rlike($field, $value) {}
	
	/**
	 * condici�n "entre 2 valores": campo between valor1 and valor2
	 * @param string $field campo
	 * @param mixed $lower valor m�nimo
	 * @param mixed $higher valor m�ximo
	 * @return string expresi�n sql
	 */
	static public function btw($field, $lower, $higher) {}
	
	/**
	 * condici�n "menor que": campo < valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function lt($field, $value) {}
	
	/**
	 * condici�n "menor o igual a": campo <= valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function elt($field, $value) {}
	
	/**
	 * condici�n "mayor que": campo > valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function gt($field, $value) {}
	
	/**
	 * condici�n "mayor o igual a": campo >= valor
	 * @param string $field campo
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function egt($field, $value) {}
	
	/**
	 * condici�n con subconsulta: campo IN (subconsulta)
	 * @param string $field campo
	 * @param string $subquery subconsulta
	 * @return string expresi�n sql
	 */
	static public function sub($field, $subquery) {}
	
	/**
	 * condici�n de b�squeda por texto
	 * @param mixed $field campo(s)
	 * @param mixed $value valor
	 * @return string expresi�n sql
	 */
	static public function src($fields, $value) {}
	
	/**
	 * condici�n de b�squeda por expresi�n regular
	 * @param string $field campo
	 * @param string $regexp expresi�n regular
	 * @return string expresi�n sql
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