<?php
/**
 * @name Model
 * @package framework
 * Esquema MVC: Modelo de trabajo de informaci�n
 * @author DSD
 * @version 1.1.2
 */

class Model {
	/**
	 * array con par�metros establecidos
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
	 * total de registros obtenidos en la �ltima consulta
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
	 * par�metros de filtraci�n de la cosulta
	 *
	 * @var filtering
	 */
	public $filtering;

	/**
	 * par�metros de orden de la cosulta
	 *
	 * @var ordering
	 */
	public $ordering;

	/**
	 * par�metros de agrupaci�n de la cosulta
	 *
	 * @var grouping
	 */
	public $grouping;

	/**
	 * par�metros de paginaci�n de la cosulta
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
	 * Recupera datos del modelo seg�n una consulta
	 *
	 * @return bool true si se ejecut� correctamente
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
	 * Establece un par�metro
	 *
	 * @param string $param par�metro
	 * @param mixed $value valor
	 */
	function param_set($param, $value) {
		$this->params[$param] = $value;
	}

	/**
	 * Obtiene el valor de un par�metro
	 *
	 * @param string $param
	 * @return mixed valor del par�metro
	 */
	function param_get($param) {
		return isset($this->params[$param]) ? $this->params[$param] : false;
	}

	/**
	 * Obtiene el valor de un par�metro en formato SQL (comillas)
	 *
	 * @param string $param nombre del par�metro
	 * @return string valor del par�metro
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
	 * Elimina todos los par�metros establecidos
	 *
	 */
	function param_clear() {
		$this->params = array();
	}

	/**
	 * Restablece la consulta, filtros y par�metros de ordenamiento, agrupaci�n, paginaci�n
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
 * Par�metros de filtrado de una consulta
 * @version 1.1.1
 */
class Filtering {
	/**
	 * Agrupaci�n de par�metros
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
 * Par�metros de ordenamiento de una consulta
 * @version 1.1.1
 */
class Ordering {
	/**
	 * Agrupaci�n de par�metros
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
	 * Agrega un par�metro de ordenamiento
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function add( $field, $order = "ASC" ) { array_push( $this->orders, "$field $order" ); }
	/**
	 * Elimina todos los par�metros establecidos
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function clear() { $this->orders = array(); }
	/**
	 * Devuelve todos los par�metros formateados en modo SQL (order by)
	 *
	 * @return string
	 */
	function get() { if ( count($this->orders) > 0 ) { return " ORDER BY " . implode(", ", $this->orders); } }
}
/**
 * Clase: Grouping
 * Par�metros de agrupado de una consulta
 * @version 1.1.1
 */
class Grouping {
	/**
	 * Agrupaci�n de par�metros
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
	 * Agrega un par�metro de agrupaci�n
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function add( $group ) { array_push( $this->groups, $group ); }
	/**
	 * Elimina todos los par�metros establecidos
	 *
	 * @param string $filter filtro en formato SQL
	 */
	function clear() { $this->groups = array(); }
	/**
	 * Devuelve todos los par�metros formateados en modo SQL (group by)
	 *
	 * @return string
	 */
	function get() { if ( count($this->groups) > 0 ) { return " GROUP BY " . implode(", ", $this->groups); } }

}
/**
 * Clase: Paging
 * Par�metros de paginaci�n de una consulta
 * @version 1.1.1
 */
class Paging {
	/**
	 * Filas por p�gina
	 *
	 * @var int
	 */
	public $page_rows = null;
	/**
	 * N�mero de p�gina
	 *
	 * @var int
	 */
	public $page = null;
	/**
	 * Total de p�ginas
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
	 * Establece los par�metros de paginaci�n
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
	 * Restablece los valores de paginaci�n
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
	 * Devuelve los valores de paginaci�n formateados en modo SQL (limit)
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