<?php
/**
 *
 */

	class html_lists {
	function html_ul_begin($id = null, $class = null, $options = null) {
		if ($id)	{ $id	= "id=\"$id\""; }
		if ($class)	{ $class = "class=\"$class\""; }
		echo "<ul {$id} {$class}" . ($options ? $options : "") . ">";
		return true;
	}

	function html_ul_end() {
		echo "</ul>";
		return true;
	}

	function li($id = null, $class = null, $options = null) {
		if ($id)	{ $id	= "id=\"$id\""; }
		if ($class)	{ $class = "class=\"$class\""; }
		echo "<li {$id} {$class}" . ($options ? $options : "") . ">";
		return true;
	}

	function li_end() {
		echo "</li>";
		return true;
	}
	}
?>
