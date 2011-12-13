<?php
/**
 * Vínculo entre Objetos de Datos
 * @name DataObjectLink
 * @version 1.2
 */
final class DataObjectLink extends DataObject {
	private $_structure = array(
		'dataobject' => null,
		'key' => null
	);
	private $_item = null;
	
	public function __construct() {
		$args = func_get_args();
		$dataobject = isset($args[0]) ? $args[0] : null;
		$key = isset($args[1]) ? $args[1] : null;
		$this->define($dataobject, $key);
	}
	
	public function define($dataobject = null, $key = null) {
		if ( $dataobject ) {
			if (! class_exists($dataobject, false) ) { throw new Exception(); }
			$this->_structure['dataobject'] = $dataobject;
		}
		if ( $key ) { $this->_structure['key'] = $key; }
		$this->_item = new $this->_structure['dataobject'];
	}
	
	public function __get($property) {
		if (is_null($this->_item)) { throw new Exception(); }
		if (! property_exists($this->_item, $property) ) { throw new Exception(); }
		return $this->_item->$property;
	}
	
	public function __set($property, $value) {
		if (is_null($this->_item)) { throw new Exception(); }
		if (! property_exists($this->_item, $property) ) { throw new Exception(); }
		$this->_item->$property = $value;
	}
	
	public function __call($function, $params) {
		if (is_null($this->_item)) { throw new Exception(); }
		switch ($function) {
			case 'load': $this->_item->load(); break;
			case 'bind': $this->_item->bind(); break;
			case property_exists($this->_item, $property):
				$this->_item->__call($property, $params);
				break;
			default:
				throw new Exception();
		}
	}
	
	public function __toString() {
		if ($this->_item instanceof $this->_structure['dataobject']) {
			return $this->_item->{$this->_structure['key']};
		} else {
			return "";
		}
	}
}
?>