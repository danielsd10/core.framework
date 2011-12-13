<?php
/**
 * @name File
 * @package framework.dataobjects
 * Objeto manejador de archivos
 * @author DSD
 * @version 1.0
 */

class File {
	private $filename = null;

	const name_current = 0;
	const name_client = 1;
	const name_random = 2;

	function __construct($basedir = null) {
		if (! is_null($basedir) ) {
			$basedir = trim( $basedir );
			$basedir = rtrim( $basedir , "/" );
			$basedir = rtrim( $basedir , "\\" );
			$this->filename = $basedir . DS . ".";
		}
	}

	function is_valid() {
		if (is_null( $this->filename )) return false;
		return file_exists( $this->filename );
	}

	function unlink() { $this->filename = null; }
	function link( $filename ) {
		$filename = is_null($filename) ? "." : $filename;
		$this->filename = $this->get_dirname() . DS . $filename;
		if (! $this->is_valid() ) {
			$this->filename = null;
			return false;
		}
		return true;
	}

	function get() { return $this->filename; }
	function __toString() {	return $this->filename; }

	function get_url() {
		global $f;
		if (! $this->is_valid()) return false;
		$str = $f->config->url->base . str_ireplace( DIR_BASE, "", $this->filename );
		if ( DS != "/" ) { $str = str_replace( DS, "/", $str ); }
		return $str;
	}

	function get_sql() {
		global $f;
		$str = $this->get_basename();
		if ( strlen($str) == 0 ) { return "NULL"; }
		if ( $str == "." ) { return "NULL"; }
		$str = utf8_decode( $str );
		$str = $f->database->get_escaped( $str );
		$str = $f->database->get_quoted( $str );
		return $str;
	}

	function get_size() { return $this->is_valid() ? filesize( $this->filename ) : false; }
	function get_md5() { return $this->is_valid() ? hash_file('MD5', $this->filename ) : null; }
	function get_crc32() { return $this->is_valid() ? hash_file('CRC32', $this->filename ) : null; }
	function get_basename() { return $this->is_valid() ? basename( $this->filename ) : null; }
	function get_dirname() { return $this->is_valid() ? dirname( $this->filename ) : null; }
	function get_extension() {
		$info = $this->is_valid() ? pathinfo( $this->filename ) : null;
		if ( is_null($info) ) return false;
		return $info['extension'];
	}

	function read() { return  $this->is_valid() ? file_get_contents($this->filename) : false; }

	function write( $content ) {
		if (! $this->is_valid()) return false;
		if (! file_put_contents( $this->filename, $content )) return false;
		return true;
	}

	function append( $content ) {
		if (! $this->is_valid()) return false;
		if (! file_put_contents( $this->filename, $content, FILE_APPEND )) return false;
		return true;
	}

	function rename( $new_name ) {
		if (! $this->is_valid() ) return false;
		$new_name = $this->get_dirname() . DS . $new_name;
		if (! rename( $this->filename, $new_name )) return false;
		$this->filename = $new_name;
		return true;
	}

	function rename_random( $lenght = 6, $extension = "" ) {
		if (! $this->is_valid() ) return false;
		$new_name = $this->get_dirname() . DS . $this->random( $lenght ) . $extension;
		if (! rename( $this->filename, $new_name )) return false;
		$this->filename = $new_name;
		return true;
	}

	function move( $to ) {
		if (! $this->is_valid() ) return false;
		if (! file_exists($to) ) return false;
		$new_name = $to . DS . $this->get_basename();
		if (! rename( $this->filename, $new_name )) return false;
		$this->filename = $new_name;
		return true;
	}

	function copy( $to ) {
		if (! $this->is_valid()) return false;
		if (! copy( $this->filename, $to )) return false;
		return true;
	}

	function delete() {
		if (! $this->is_valid()) return true;
		if (! unlink( $this->filename )) return false;
		$this->filename = null;
		return true;
	}

	function set_upload( $filefield, $filekey = null, $replace = true ) {
		global $f;

		if ( $filekey ) {
			$temp_path = $f->uploads[$filefield]['tmp_name'][$filekey];
			$new_name = $f->uploads[$filefield]['name'][$filekey];
		} else {
			$temp_path = $f->uploads[$filefield]['tmp_name'];
			$new_name = $f->uploads[$filefield]['name'];
		}

		/*switch ($name) {
			case self::name_client : $new_path = $this->get_dirname() . DS . $new_name; break;
			case self::name_random : $new_path = $this->get_dirname() . DS . $this->random(); break;
			case self::name_current : $new_path = ($this->get_basename() == ".") ? $this->get_dirname() . DS . $this->random() : $this->filename; break;
			default: $new_path = $this->get_dirname() . DS . $name;
		}*/

		if ( $this->get_basename() != "." ) { $new_name = $this->get_basename(); }

		if ( file_exists( $this->get_dirname() . DS . $new_name )  && !$replace ) {
			$new_path = $this->get_dirname() . DS . basename($new_name, $this->get_extension()) . "_" . $this->random() . ((strlen($this->get_extension()) > 0 ) ? "." . $this->get_extension() : "");
		} else {
			$new_path = $this->get_dirname() . DS . $new_name;
		}

		if (is_uploaded_file( $temp_path )) {
			if ( move_uploaded_file( $temp_path, $new_path ) ) {
				$this->filename = $new_path;
			} else {
				$this->filename = null;
				return false;
			}
		} else {
			return false;
		}

		return true;
	}

	private function pathinfo($part) {
		//dirname - basename - extension - filename
		$path_info = pathinfo( $this->filename );
		return $path_info[$part];
	}

	private function random($lenght = 6) {
		$str = "";
		for($i = 1; $i <= $lenght; $i++) {
			$part = mt_rand(0, 2);
			switch ( $part ) {
				case 0: $str .= mt_rand(0, 9); break;
		    	case 1: $str .= chr(mt_rand(65, 90)); break;
		    	case 2: $str .= chr(mt_rand(97, 122)); break;
			}
		}
		return $str;
	}
}

?>