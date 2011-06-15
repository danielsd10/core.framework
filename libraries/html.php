<?php
/**
 *
 */

	class html {

		/** listas */

		function ul($id = null, $class = null, $options = null) {
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<ul {$id} {$class}" . ($options ? $options : "") . ">";
			return true;
		}

		function ul_end() {
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

		/** elementos de bloque **/

		function span($id = null, $class = null, $options = null) {
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<span {$id} {$class}" . ($options ? $options : "") . ">";
			return true;
		}

		function span_end() {
			echo "</span>";
			return true;
		}

		function div($id = null, $class = null, $options = null) {
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<div {$id} {$class}" . ($options ? $options : "") . ">";
			return true;
		}

		function div_end() {
			echo "</div>";
			return true;
		}

		/** formularios **/

		function input_text($name = null, $id = null, $value = null, $class = null, $options = null) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($value)	{ $value = "value=\"$value\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<input type=\"text\" {$name} {$id} {$value} {$class}" . ($options ? $options : "") . " />";
			return true;
		}

		function input_password($name = null, $id = null, $value =null, $class = null, $options = null) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($value)	{ $value = "value=\"$value\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<input type=\"password\" {$name} {$id} {$value} {$class}" . ($options ? $options : "") . " />";
			return true;
		}

		function html_input_hidden($name = null, $id = null, $value = null, $class = null, $options = null) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($value)	{ $value = "value=\"$value\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<input type=\"hidden\" {$name} {$id} {$value} {$class}" . ($options ? $options : "") . " />";
			return true;
		}

		function input_checkbox($name = null, $id = null, $checked = false, $value = null, $class = null, $options = null) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($value)	{ $value = "value=\"$value\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			if ($checked)	{ $checked = "checked=\"$checked\""; }
			echo "<input type=\"checkbox\" {$name} {$id} {$value} {$class} {$checked}" . ($options ? $options : "") . " />";
			return true;
		}

		function html_input_radio($name = null, $id = null, $value = null, $class = null, $options = null) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($value)	{ $value = "value=\"$value\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<input type=\"radio\" {$name} {$id} {$value} {$class}" . ($options ? $options : "") . " />";
			return true;
		}

		/**
		 * Salida HTML de un area de texto (textarea)
		 * @return boolean
		 * @param string $name[optional] nombre
		 * @param string $id[optional] id
		 * @param string $value[optional] valor inicial
		 * @param int $rows[optional] filas
		 * @param int $cols[optional] columnas
		 * @param string $class[optional] clase
		 * @param string $options[optional] otras propiedades
		 */
		function textarea($name = null, $id = null, $value = null, $rows = null, $cols = null, $class = null, $options = null) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			if ($rows)	{ $rows = "rows=\"$rows\""; }
			if ($cols)	{ $cols = "cols=\"$cols\""; }
			echo "<textarea {$name} {$id} {$class} {$rows} {$cols}" . ($options ? $options : "") . ">" . ($value ? $value : "") . "</textarea>";
			return true;
		}

		/**
		 * Salida HTML de una lista despegable (select)
		 * @return boolean
		 * @param string $name[optional]
		 * @param string $id[optional]
		 * @param array $list[optional]
		 * @param string $sel[optional]
		 * @param string $value[optional]
		 * @param string $class[optional]
		 * @param string $options[optional]
		 */
		function select($name = null, $id = null, $list = array(), $sel = null, $value = null, $class = null, $options = null ) {
			if ($name)	{ $name	= "name=\"$name\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($value)	{ $value = "value=\"$value\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			echo "<select {$name} {$id} {$value} {$class} " . ($options ? $options : "") . ">\n\t";
			foreach($list as $key => $val) {
				if ( (string) $key == (string) $sel ){
					echo "<option value=\"" . $key . "\" selected=\"selected\">" .$val . "</option>\n\t";
				}else{
					echo "<option value=\"" . $key . "\">" .$val . "</option>\n\t";
				}
			}
			echo "</select>";
			return true;
		}

		function label($for = null, $value = null, $use_colon = true, $id = null, $class = null, $options = null) {
			if ($for)	{ $for	= "for=\"$for\""; }
			if ($id)	{ $id	= "id=\"$id\""; }
			if ($class)	{ $class = "class=\"$class\""; }
			if ($value)	{ $value = $value . ( $use_colon ? ":" : "") ; }
			echo "<label {$for} {$id} {$class}" . ($options ? $options : "") . ">" . $value . "</label>";
			return true;
		}

	}

?>