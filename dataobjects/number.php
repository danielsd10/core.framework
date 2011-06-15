<?php
/**
 * @name Number
 * @package framework.dataobjects
 * Objeto manejador de valores numéricos
 * @author DSD
 * @version 1.0
 */

class Number {
	private $number = null;

	function __construct($number = null) {
		if ( $number ) {
			$this->number = (float) $number;
		} else {
			$this->number = null;
		}
	}

	function clear() { $this->number = null; }
	function set( $new_value ) { $this->number = is_null($new_value) ? null : is_numeric($new_value) ? $new_value : null; }
	function get() { return $this->number; }
	function __toString() {	return (string) $this->number; }

	function get_sql() {
		if ( is_null($this->number) ) { return "NULL"; }
		return $this->number;
	}

	function is_integer() { return is_null($this->number) ? false : (is_integer($this->number)); }
	function is_positive() { return is_null($this->number) ? false : ($this->number >= 0); }
	function is_negative() { return is_null($this->number) ? false : ($this->number < 0); }

	function get_ceil() { return is_null($this->number) ? null : ceil( $this->number );	}
	function get_floor() { return is_null($this->number) ? null : floor( $this->number); }
	function get_round( $precision = null ) { return is_null($this->number) ? null : round($this->number, $precision ); }
	function get_hex() { return is_null($this->number) ? null : dechex( $this->number ); }
	function get_oct() { return is_null($this->number) ? null : decoct( $this->number ); }
	function get_bin() { return is_null($this->number) ? null : decbin( $this->number ); }
	function get_ip() { return is_null($this->number) ? null : long2ip( $this->number ); }
	function get_format($decimals = 2, $decimals_sep = '.', $thousands_sep = '') { return is_null($this->number) ? null : number_format( $this->number, $decimals, $decimals_sep, $thousands_sep ); }
	function get_mask($lenght, $mask = "0") { return is_null($this->number) ? null : sprintf("%'{$mask}{$lenght}s", $this->number); }

	function set_random($min = 0, $max = RAND_MAX) { $this->number = mt_rand( $min, $max ); }
	function set_ip( $ip ) { $this->number = ip2long( $ip ); }

	function get_words() {
		if ( is_null($this->number) ) { return null; }
		$i = 0;
		$j = 0;

		$tmp = sprintf("%012d", $this->number);
		$str = "";

		if ( strlen($tmp) > 12) { return ""; }

		# zero is a special case.
		# you may want to change this to "no"
		# as in "no dollars and 12/100" for writing checks.
		if ( (float) $tmp == 0 ) { return "CERO"; }

		$i = (float) substr($tmp, 0, 3 );
		if ($i != 0 ) {
			if ($i != 1) { self::do_hundreds($i, $str); }
			$str .= " MIL";
		}

		$i = (float) substr($tmp, 3, 3 );
		if ($i != 0 ) {
			if ($i != 1) {
				self::do_hundreds($i, $str);
			} else {
				$str .= " UN";
			}
			$str .= " MILLON" . (($i != "1") ? "ES" : "");
		}

		$i = (float) substr($tmp, 6, 3 );
		if ($i != 0 ) {
			if ($i != 1) { self::do_hundreds($i, $str); }
			$str .= " MIL";
		}

		$i = (float) substr($tmp, -3 );
		if ($i != 0 ) {
			self::do_hundreds($i, $str);
		}

		return $str;
	}

	private function do_hundreds($i, &$str) {
		if ($i > 99) {
	   		$j = $i;
	   		$i = (int) ($i / 100);

			switch (true) {
				case ($j == 100): $str .= " CIEN"; break;
				case ($j > 100 && $j <= 199): $str .= " CIENTO"; break;
				case ($j >= 200 && $j <= 299): $str .= " DOSCIENTOS"; break;
				case ($j >= 300 && $j <= 399): $str .= " TRESCIENTOS"; break;
				case ($j >= 400 && $j <= 499): $str .= " CUATROCIENTOS"; break;
				case ($j >= 500 && $j <= 599): $str .= " QUINIENTOS"; break;
				case ($j >= 600 && $j <= 699): $str .= " SEISCIENTOS"; break;
				case ($j >= 700 && $j <= 799): $str .= " SETECIENTOS"; break;
				case ($j >= 800 && $j <= 899): $str .= " OCHOCIENTOS"; break;
				case ($j >= 900 && $j <= 999): $str .= " NOVECIENTOS"; break;
			}

			$i = $j % 100;
		}

		if ($i != 0 ) {
			self::do_tens($i, $str);
		}

		return;
	}

	private function do_tens($i, &$str) {
		$x = $i % 100;
		switch (true) {
			case ($x >= 90 && $x <= 99): $str .= " NOVENTA"; self::do_ones($i, $str); break;
			case ($x >= 80 && $x <= 89): $str .= " OCHENTA"; self::do_ones($i, $str); break;
			case ($x >= 70 && $x <= 79): $str .= " SETENTA"; self::do_ones($i, $str); break;
			case ($x >= 60 && $x <= 69): $str .= " SESENTA"; self::do_ones($i, $str); break;
			case ($x >= 50 && $x <= 59): $str .= " CINCUENTA"; self::do_ones($i, $str); break;
			case ($x >= 40 && $x <= 49): $str .= " CUARENTA"; self::do_ones($i, $str); break;
			case ($x >= 30 && $x <= 39): $str .= " TREINTA"; self::do_ones($i, $str); break;
			//case ($x >= 20 && $x <= 29): $str .= " VEINTE"; self::do_ones($i, $str); break;
			case ($x == 29): $str .= " VEINTINUEVE"; break;
			case ($x == 28): $str .= " VEINTIOCHO"; break;
			case ($x == 27): $str .= " VEINTICIETE"; break;
			case ($x == 26): $str .= " VEINTISEIS"; break;
			case ($x == 25): $str .= " VEINTICINCO"; break;
			case ($x == 24): $str .= " VEINTICUATRO"; break;
			case ($x == 23): $str .= " VEINTITRES"; break;
			case ($x == 22): $str .= " VEINTIDOS"; break;
			case ($x == 21): $str .= " VEINTIUNO"; break;
			case ($x == 20): $str .= " VEINTE"; break;
			case ($x == 19): $str .= " DIECINUEVE"; break;
			case ($x == 18): $str .= " DIECIOCHO"; break;
			case ($x == 17): $str .= " DIECISIENTE"; break;
			case ($x == 16): $str .= " DIECISEIS"; break;
			case ($x == 15): $str .= " QUINCE"; break;
			case ($x == 14): $str .= " CATORCE"; break;
			case ($x == 13): $str .= " TRECE"; break;
			case ($x == 12): $str .= " DOCE"; break;
			case ($x == 11): $str .= " ONCE"; break;
			case ($x == 10): $str .= " DIEZ"; break;
			default: self::do_ones($i, $str); break;
		}

		return;
	}

	private function do_ones($i, &$str) {
		if ($i < 10 || $i % 10 == 0) {
			$str .= " ";
		} else {
			$str .= " Y ";
		}

		$x = $i % 10;
		switch ($x) {
			case 9: $str .= "NUEVE"; break;
			case 8: $str .= "OCHO"; break;
			case 7: $str .= "SIETE"; break;
			case 6: $str .= "SEIS"; break;
			case 5: $str .= "CINCO"; break;
			case 4: $str .= "CUATRO"; break;
			case 3: $str .= "TRES"; break;
			case 2: $str .= "DOS"; break;
			case 1: $str .= "UNO"; break;
		}

		return;
	}
}

?>