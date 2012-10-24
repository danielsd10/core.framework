<?php
abstract class String {
	/* constantes de codificación */
	const ascii = 0x1;
	const utf8 = 0x2;
	const latin1 = 0x4;
	
	/* constantes de formato de texto. función: is() */
	const hex = 0x10;
	const date = 0x20;
	const ip = 0x40;
	const email = 0x80;
	const url = 0x100;
	
	/* constantes de nivel de aleatorio. función: rnd() */
	const numbers = 0x1;
	const uchars = 0x2;
	const lchars = 0x4;
	const symbols = 0x8;
	
	/**
	 * devuelve la logitud de una cadena
	 * @param string $string cadena de texto
	 * @return int longitud de cadena
	 */
	public function len($string) {}

	/**
	 * convierte una texto a html
	 * @param string $string cadena de texto
	 * @return string cadena convertida a html
	 */
	public function html($string) {}
	
	/**
	 * examina la codificación de un texto
	 * @param string $string cadena de texto
	 * @return string codificación detectada
	 */
	public function encoding($string) {}

	/**
	 *
	 * convierte un texto
	 * @param unknown_type $string
	 */
	public function utf8($string) {}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $string
	 */
	public function latin1($string) {}

	/**
	 * devuelve un texto codificado con el algoritmo MD5
	 * @param string $string cadena de texto
	 * @return string
	 */
	public function md5($string) {}
	
	/**
	 * devuelve un texto codificado con el algoritmo SHA1
	 * @param string $string cadena de texto
	 * @return string
	 */
	public function sha1($string) {}
	
	/**
	 * devuelve la cadena de texto convertida a mayúsculas
	 * @param string $string cadena de texto
	 * @return string
	 */
	public function uc($string) {}
	
	/**
	 * devuelve la cadena de texto convertida a mayúsculas
	 * @param string $string cadena de texto
	 * @return string
	 */
	public function ucf($string) {}
	
	public function ucw($string) {}
	
	public function lc($string) {}

	public function rnd($length = 6, $level = 0xF) {
		$str = "";
		$levels = array();
		for($i = 1; $i <= $length; $i++) {
			if ( $level & self::numbers ) { $levels[] = self::numbers; }
			if ( $level & self::uchars ) { $levels[] = self::uchars; }
			if ( $level & self::lchars ) { $levels[] = self::lchars; }
			if ( $level & self::symbols ) { $levels[] = self::symbols; }
			$part = mt_rand(0, size($levels));
			switch ( $levels[$part] ) {
				case self::numbers: $str .= mt_rand(0, 9); break;
		    	case self::uchars: $str .= chr(mt_rand(65, 90)); break;
		    	case self::lchars: $str .= chr(mt_rand(97, 122)); break;
		    	case self::symbols:
		    		$sub_part = mt_rand(0, 3);
		    		switch ( $sub_part ) {
		    			case 0: $str .= chr(mt_rand(33, 47)); break;
		    			case 1: $str .= chr(mt_rand(58, 64)); break;
		    			case 2: $str .= chr(mt_rand(91, 96)); break;
		    			case 3: $str .= chr(mt_rand(123, 126)); break;
		    		}
			}
		}
		return $str;
	}
	
	public function is($string, $check) {
		$is = false;
		
		return $is;
	}
}
?>