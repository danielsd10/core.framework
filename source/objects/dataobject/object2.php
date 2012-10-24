<?php
class DataObject {
	
	
	public function __call($function, $params = array()) {
		switch ($function) {
			case 'load':
			case 'bind':
			case 'store':
			case 'remove':
				if (strlen($params[0]) > 0) { $function = "{$function}_{$params[0]}"; }
				if (! method_exists($this, $function) ) { throw Application::Exception('Mod003', array(get_class($this), $function)); }
				if (tracing()) Trace::info("Ejecutando el metodo " . $function, 'Model::' . get_class($this));
				call_user_func(array($this, $function));
				break;
			case 'param':
			case 'params':
				$this->_params($params[0]);
				break;
			default:
				throw new Exception('método no existe');
				break;
		}
		
		return $this;
	}
}