<?php
/**
 * @name Collection
 * @package framework.dataobjects
 * Objeto manejador de colecciones de otros objetos
 * @author DSD
 * @version 1.0
 */

class Collection {
	/**
	 * nombre de la clase a la cual se relaciona la colección
	 *
	 * @var string
	 */
	private $object_type = null;

	/**
	 * elementos de la colección
	 *
	 * @var array
	 */
	private $items;

	/**
	 * constructor
	 *
	 * @param string $object_type
	 */
	function __construct($object_type = null) {
		if ( $object_type ) {
			$this->object_type = $object_type;
		}
		$this->items = array();
	}

	/**
	 * carga elementos a la colección
	 *
	 * @param string $field
	 * @param string $from
	 * @param string $key
	 * @param variant $value
	 * @param bool $autoBind
	 * @return array
	 */
	function load( $field = "id", $from, $key, $value, $autoBind = true ) {
		global $f;
		$sql = "SELECT {$field} FROM {$from} WHERE {$key} = {$value}";

		$rs = $f->database->execute( $sql );
		if ($rs === false) { return false; }
		elseif ( $f->database->total_records() == 0 ) { return false; }
		elseif ( ! $autoBind ) {
			while ( true ) {
				$record = $f->database->get_record();
				if ( !$record ) { break; }
				$records[] = $record;
			}
			return $records;
		} else {
			while ( true ) {
				$record = $f->database->get_record();
				if ( !$record ) { break; }
				$records[] = $record;
			}
			foreach ( $records as $record ) {
				$i = $this->add();

				$item = $this->item($i);
				if ( method_exists($item, 'load') ) { $item->load ( $record->$field ); }
			}
			return $records;
		}
	}

	function bind( $from, $ignore = array() ) {
		if (! is_array($from) ) { return false; }

		if (!is_array( $ignore )) { $ignore = explode( ' ', $ignore ); }

		foreach ( $from as $elem ) {
			$i = $this->add();
			$item = $this->item($i);

			if ( method_exists($item, 'bind') ) { $item->bind ( $elem ); }
		}
		return true;
	}

	function item( $index ) {
		if ( isset($this->items[ $index ]) ) {
			return $this->items[ $index ];
		}
	}

	function items() { return $this->items; }

	function clear() { unset($this->items); $this->items = array(); }

	function count() { return count($this->items); }

	function get_type() { return $this->object_type; }

	function add() {
		if ( is_null($this->object_type) ) { return false; }
		$elem = new $this->object_type;
		$this->items[] = $elem;
		return (count($this->items) - 1);
	}

	function append( $new_element ) {
		if ( ! is_object($new_element) || is_null($this->object_type) ) { return false; }
		if (  get_class($new_element) == $this->object_type ) {
			$this->items[] = $new_element;
			return (count($this->items) - 1);
		} else {
			return false;
		}
	}

	function replace( $index, $new_element ) {
		if ( ! is_object($new_element) || is_null($this->object_type) ) { return false; }
		if (  get_class($new_element) == $this->object_type ) {
			$this->items[$index] = $new_element;
			return true;
		} else {
			return false;
		}
	}

	function remove($index) {
		if ( isset( $this->items[ $index ] ) ) {
			unset ( $this->items[ $index ] );
		}
	}

}

?>