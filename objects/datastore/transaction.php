<?php
/**
 * Transacción de base de datos
 * @name Dataset
 * @version 1.2
 */
class Transaction {
	
	public $state;
	public $affectedRows;
	public $lastID;
	public $lastError;
	
	private $_statement = array();
	
	public function insert($table_name, $fields = array()) {
		$this->_statement['insert'] = $table_name;
		foreach ($fields as $field) {
			$this->_statement['values'][$field] = null;
		}
	}
	
	public function update($table_name, $fields = array()) {
		$this->_statement['update'] = $table_name;
		foreach ($fields as $field) {
			$this->_statement['values'][$field] = null;
		}
	}
	
	public function delete($table_name) {
		$this->_statement['delete'] = $table_name;
	}
	
	public function where($condition) {
		$this->_statement['where'][] = $condition;
	}
	
	public function values($values = array()) {
		foreach ($values as $field => $value) {
			$this->_statement['values'][$field] = $values;
		}
	}
	
	public function execute() {
		$this->lastID = null;
		$this->affectedRows = null;
		
		$appliction = Application::getInstance();
		try {
			$appliction->datastore->execute($sql);
			$this->affectedRows = $appliction->datastore->affectedRows;
			$this->lastID = $appliction->datastore->lastID;
		} catch(Exception $e) {
			
		}
		$this->_statement = array();
	}
}
?>