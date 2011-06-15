<?php
/**
 * @name Model
 * @package framework
 * Esquema MVC: Modelo de trabajo de información
 * @author DSD
 * @version 1.1.2
 */

class Model {
	/**
	 * array con parámetros establecidos
	 *
	 * @var array
	 */
	public $params;

	/**
	 * array con registros obtenidos de la base de datos
	 *
	 * @var array
	 */
	public $records;

	/**
	 * total de registros obtenidos en la última consulta
	 *
	 * @var int
	 */
	public $total_records;

	/**
	 * texto de consulta a la base de datos
	 *
	 * @var string
	 */
	public $query;

	/**
	 * parámetros de filtración de la cosulta
	 *
	 * @var filtering
	 */
	public $filtering;

	/**
	 * parámetros de orden de la cosulta
	 *
	 * @var ordering
	 */
	public $ordering;

	/**
	 * parámetros de agrupación de la cosulta
	 *
	 * @var grouping
	 */
	public $grouping;

	/**
	 * parámetros de paginación de la cosulta
	 *
	 * @var paging
	 */
	public $paging;

	/**
	 * Constructor
	 *
	 */
	function __construct() {
		$this->filtering = new Filtering;
		$this->ordering = new Ordering;
		$this->grouping = new Grouping;
		$this->paging = new Paging;
		$this->params = array();
	}

	/**
	 * Carga una clase de datos
	 *
	 * @param string $name nombre de la clase
	 */
	function load_class($name) {
		$pieces = explode("/", trim($name, "/"));
		$component = $pieces[0];
		$class = $pieces[1];

		if ( file_exists( DIR_COMPONENT.DS."{$component}/models/classes/{$class}.class.php" ) ) {
			require_once( DIR_COMPONENT.DS."{$component}/models/classes/{$class}.class.php" );
		}
	}

	/**
	 * Carga varias clases de datos
	 *
	 * @param mixed $names array o string con nombres de clases
	 */
	function load_classes($names) {
		if (! is_array($names) ) { $names = explode(" ", $names); }
		foreach ( $names as $name ) {
			$this->load_class( $name );
		}
	}

	/**
	 * Recupera datos del modelo según una consulta
	 *
	 * @return bool true si se ejecutó correctamente
	 */
	function data() {
		global $f;
		$this->query .= $this->filtering->get();
		$this->query .= $this->grouping->get();
		$this->query .= $this->ordering->get();
		$this->query .= $this->paging->get();

		$rs = $f->database->execute( $this->query );
		if ($rs === false) {
			return false;
		} else {
			$this->records = array();
			$this->total_records = 0;
			while (true) {
				$row = $f->database->get_record();
				if ( !$row ) { break; }
				array_push($this->records, $row);
				$this->total_records++;
			}
			return true;
		}
	}

	/**
	 * Establece un parámetro
	 *
	 * @param string $param parámetro
	 * @param mixed $value valor
	 */
	function param_set($param, $value) {
		$this->params[$param] = $value;
	}

	/**
	 * Obtiene el valor de un parámetro
	 *
	 * @param string $param
	 * @return mixed valor del parámetro
	 */
	function param_get($param) {
		return isset($this->params[$param]) ? $this->params[$param] : false;
	}

	/**
	 * Obtiene el valor de un parámetro en formato SQL (comillas)
	 *
	 * @param string $param nombre del parámetro
	 * @return string valor del parámetro
	 */
	function param_get_sql($param) {
		global $f;
		if (! isset($this->params[$param])) { return false; }
		if (is_array($this->params[$param])) {
			foreach ($this->params[$param] as $i=>$val) {
				$this->params[$param][$i] = $f->database->get_quoted( $f->database->get_escaped( $val ) );
			}
			$param = "(" . implode(", ", $this->params[$param]) . ")";
		} else {
			$param = $f->database->get_quoted( $f->database->get_escaped( $this->params[$param] ) );
		}
		return $param;
	}

	/**
	 * Elimina todos los parámetros establecidos
	 *
	 */
	function param_clear() {
		$this->params = array();
	}

	/**
	 * Restablece la consulta, filtros y parámetros de ordenamiento, agrupación, paginación
	 *
	 */
	function query_clear() {
		$this->query = "";
		$this->filtering->clear();
		$this->ordering->clear();
		$this->grouping->clear();
		$this->paging->clear();
	}

}

/**
 * Clase: Filtering
 * Parámetros de filtrado de una consulta
 * @version 1.1.1
 */
class Filtering {
	/**
	 * Agrupación de parámetros
	 *
	 * @var array
	 */
	public $filters;
	/**
	 * Constructor
	 *
	 */
	function __construct() { $this->filters = array(); }
	/**
	 * Agrega un filtro
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function add( $filter ) { array_push( $this->filters, $filter ); }
	/**
	 * Elimina todos los filtros establecidos
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function clear() { $this->filters = array(); }
	/**
	 * Devuelve todos los filtros formateados en modo SQL (where)
	 *
	 * @return string
	 */
	function get() { if ( count($this->filters) > 0 ) { return " WHERE " . implode(" AND ", $this->filters); } }
}
/**
 * Clase: Ordering
 * Parámetros de ordenamiento de una consulta
 * @version 1.1.1
 */
class Ordering {
	/**
	 * Agrupación de parámetros
	 *
	 * @var array
	 */
	public $orders;
	/**
	 * Constructor
	 *
	 */
	function __construct() { $this->orders = array(); }
	/**
	 * Agrega un parámetro de ordenamiento
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function add( $field, $order = "ASC" ) { array_push( $this->orders, "$field $order" ); }
	/**
	 * Elimina todos los parámetros establecidos
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function clear() { $this->orders = array(); }
	/**
	 * Devuelve todos los parámetros formateados en modo SQL (order by)
	 *
	 * @return string
	 */
	function get() { if ( count($this->orders) > 0 ) { return " ORDER BY " . implode(", ", $this->orders); } }
}
/**
 * Clase: Grouping
 * Parámetros de agrupado de una consulta
 * @version 1.1.1
 */
class Grouping {
	/**
	 * Agrupación de parámetros
	 *
	 * @var array
	 */
	public $groups;
	/**
	 * Constructor
	 *
	 */
	function __construct() { $this->groups = array(); }
	/**
	 * Agrega un parámetro de agrupación
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function add( $group ) { array_push( $this->groups, $group ); }
	/**
	 * Elimina todos los parámetros establecidos
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function clear() { $this->groups = array(); }
	/**
	 * Devuelve todos los parámetros formateados en modo SQL (group by)
	 *
	 * @return string
	 */
	function get() { if ( count($this->groups) > 0 ) { return " GROUP BY " . implode(", ", $this->groups); } }

}
/**
 * Clase: Paging
 * Parámetros de paginación de una consulta
 * @version 1.1.1
 */
class Paging {
	/**
	 * Filas por página
	 *
	 * @var int
	 */
	public $page_rows = null;
	/**
	 * Número de página
	 *
	 * @var int
	 */
	public $page = null;
	/**
	 * Total de páginas
	 *
	 * @var int
	 */
	public $pages = null;
	/**
	 * Total de registros
	 *
	 * @var int
	 */
	public $rows = null;
	/**
	 * Establece los parámetros de paginación
	 *
	 * @param int $page
	 * @param int $page_rows
	 * @param string $count_query
	 */
	function set( $page=1, $page_rows=20, $count_query ) {
		global $f;
		$rs = $f->database->execute( $count_query );
		if ( $rs === false ) { return false; }
		if (! $f->database->total_records() > 0 ) { return false; }
		$row = $f->database->get_record();
		$this->page = $page;
		$this->page_rows = $page_rows;
		$this->rows = isset($row->COUNT) ? $row->COUNT : (isset($row->count) ? $row->count : null);
		$this->pages = ceil( $this->rows / $this->page_rows );
	}
	/**
	 * Restablece los valores de paginación
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function clear() {
		$this->page_rows = null;
		$this->page = null;
		$this->pages = null;
		$this->rows = null;
	}
	/**
	 * Devuelve los valores de paginación formateados en modo SQL (limit)
	 *
	 * @return string
	 */
	function get() {
		if ( $this->page && $this->rows ) {
			return " LIMIT " . $this->page_rows . " OFFSET " . ($this->page_rows * ($this->page - 1));
		} elseif ( $this->rows ) {
			return " LIMIT " . $this->rows;
		}
	}
}
?>