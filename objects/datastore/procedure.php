<?php
/**
 * Procedimiento almacenado de base de datos
 * @name Procedure
 * @version 1.2
 */
class Procedure {
	private $_statement = array();
	
	public $datasets;
	
	public function call($proc_name) {
		$this->_statement['call'] = $proc_name;
		return $this;
	}
	
	public function param($value) {
		$this->_statement['params'][] = $value;
		return $this;
	}
	
	public function params($values = array()) {
		if (! is_array($values)) { throw new Exception(); }
		array_merge($this->_statement['params'], $values);
		return $this;
	}
	
	public function execute() {
		$this->datasets = null;
		
		$appliction = Application::getInstance();
		try {
			$appliction->datastore->query($sql);
			do {
   			$rowset = $stmt->fetchAll(PDO::FETCH_NUM);
   if ($rowset) {
       printResultSet($rowset, $i);
   }
   $i++;
			} while ($stmt->nextRowset());
		} catch(Exception $e) {
			
		}
		$this->_statement = array();
	}
	
}
?>