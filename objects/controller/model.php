<?php
/**
 * Modelo
 * @name Model
 * @version 1.2
 */
class Model {
	public $paging;
	public $params = array();
	private $data_var_name = null;
	
	public function __construct() {
		//echo 'constructor modelo';
	}
	
	public function paging($page, $items_page, $total_items) {
		$this->paging = new stdClass;
		$this->paging->page = $page;
		$this->paging->items_page = $items_page;
		$this->paging->total_items = $total_items;
		$total_pages = ceil($total_items / $items_page);
		//$total_page_items = ($page < $total_pages) ? $items_page : ($mod == 0 ? $items_page : $mod);
		switch (true) {
			case ($total_items <= $items_page):
				$total_page_items = $total_items;
				break;
			case ($page < $total_pages):
				$total_page_items = $items_page;
				break;
			default:
				$mod = $total_items % $items_page;
				$total_page_items = ($mod == 0) ? $items_page : $mod;
		}
		$this->paging->total_pages = $total_pages;
		$this->paging->total_page_items = $total_page_items;
		return $this;
	}
	
	private function _params() {
		$num_args = func_num_args();
		if ( $num_args == 1 ) {
			$param = func_get_arg(0);
			if ($param instanceof Request) {
				foreach ($param->data as $p => $v) {
					$this->params[$p] = $v;
				}
			} elseif (is_array($param) || is_object($param)) {
				foreach ($param as $p => $v) {
					$this->params[$p] = $v;
				}
			} else {
				$this->params[] = $param;
			}
		} elseif ( $num_args == 2 ) {
			$this->params[func_get_arg(0)] = func_get_arg(1);
		} else {
			throw Application::Exception('Mod004');
		}
	}
	
	public function data($dataobject = null, $var_name = null) {
		if (is_null($dataobject) && ! is_null($this->data_var_name)) {
			$var_name = $this->data_var_name;
			return $this->$var_name;
		}
		//if (! ($dataobject instanceof DataObject)) { throw new Exception(); }
		if (! $var_name ) {
			$var_name = get_class( $dataobject );
		}
		$this->data_var_name = $var_name;
		$this->$var_name = $dataobject;
		return $this;
	}
	
	/* Para utilizar la clase Model se debe crear una clase Model_{algo}
	 * Los miembros de esa clase debe ser protected ya que s�lo se ejecutar�n con overload
	 * Los nombres de m�todos deben preceder cualquiera de los siguientes verbos: create, get, save, delete
	 * create_{algo} para crear un nuevo objeto
	 * get_{algo} para obtener datos de uno o varios objetos
	 * save_{algo} para guardar o modificar datos
	 * delete_{algo} para eliminar datos
	 */
	
	/** @todo resolver envio de parametros */
	public function __call($function, $params = array()) {
		switch ($function) {
			case 'create':
			case 'get':
			case 'save':
			case 'delete':
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
				throw new Exception('m�todo no existe');
				break;
		}
		
		/* ejercer seguridad seg�n si sesi�n est� en modo estricto */
		
		/* soporte para debug */
		
		/* ejecutar funci�n */
		//try {
			//$this->$fn();
			return $this;
		//} catch (Exception $e) {
			
		//}
	}
}
?>