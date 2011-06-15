<?php
/**
 * @name Date
 * @package framework.dataobjects
 * Objeto manejador de fechas
 * @author DSD
 * @version 1.0
 */

class Date {
	private $date = null;
	private $mode = 'dt';

	const mode_date = 'd';
	const mode_time = 't';
	const mode_datetime = 'dt';

	const interval_years = 'years';
	const interval_months = 'months';
	const interval_days = 'days';
	const interval_hours = 'hours';
	const interval_minutes = 'minutes';
	const interval_seconds = 'seconds';

	function __construct( $mode = self::mode_datetime, $date = null ) {
		$this->mode = $mode;
		if ( $date ) {
			if ( is_integer( $date ) ) {
				$this->date = $date;
			} else {
				$this->date = $this->parse_date( $date );
				//$this->date = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
			}
		} else {
			$this->date = null;
		}
	}

	function clear() { $this->date = null; }
	function set( $new_value ) { $this->date = $this->parse_date( $new_value ); }
	function get() { return $this->str_date( $this->date ); }
	function __toString() {	return (string) $this->str_date( $this->date ); }

	function get_sql() {
		global $f;
		if ( is_null($this->date) ) { return "NULL"; }
		$date = $this->str_date( $this->date );
		$date = $f->database->get_quoted( $date );
		return $date;
	}

	function set_add( $interval = self::interval_seconds, $value ) {
		$base_day = date("d", $this->date);
        $base_month = date("m", $this->date);
        $base_year = date("Y", $this->date);
        $base_hour = date("H", $this->date);
        $base_minute = date("i", $this->date);
        $base_second = date("s", $this->date);
        $days = 0;
        $months = 0;
        $years = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        if ( isset( $$interval ) ) { $$interval = $value; }
        $date = mktime( $base_hour + $hours, $base_minute + $minutes, $base_second + $seconds, $base_month + $months, $base_day + $days, $base_year + $years);
        $this->date = $date;
	}

	function set_now() { $this->date = time(); }

	function get_format($format, $html = false) {
		if ( is_null( $this->date) ) { return null; }
		$date = date( $format, $this->date );
		if (substr_count($format, "F") > 0) {
			$months = Array( 1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
			$date = str_ireplace(date("F", $this->date), $months[date("n", $this->date)], $date);
		}
		if (substr_count($format, "M") > 0) {
			$months = Array( 1 => "Ene", 2 => "Feb", 3 => "Mar", 4 => "Abr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Ago", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dic");
			$date = str_ireplace(date("M", $this->date), $months[date("n", $this->date)], $date);
		}
		if (substr_count($format, "l") > 0) {
			$days = Array( 0 => "Domingo", 1 => "Lunes", 2 => "Martes", 3 => "Miércoles", 4 => "Jueves", 5 => "Viernes", 6 => "Sábado");
			$date = str_ireplace(date("l", $this->date), $days[date("w", $this->date)], $date);
		}
		if (substr_count($format, "D") > 0) {
			$days = Array( 0 => "Dom", 1 => "Lun", 2 => "Mar", 3 => "Mié", 4 => "Jue", 5 => "Vie", 6 => "Sáb");
			$date = str_ireplace(date("D", $this->date), $days[date("w", $this->date)], $date);
		}
		if ($html) $date = htmlentities($date);
		return $date;
	}

	function get_date() { return date("Y-m-d", $this->date); }

	function get_time() { return date("H:i:s", $this->date); }

	function get_timestamp() { return $this->date; }

	function get_add( $interval = self::interval_seconds, $value ) {
        $base_day = date("d", $this->date);
        $base_month = date("m", $this->date);
        $base_year = date("Y", $this->date);
        $base_hour = date("H", $this->date);
        $base_minute = date("i", $this->date);
        $base_second = date("s", $this->date);
        $days = 0;
        $months = 0;
        $years = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        if ( isset( $$interval ) ) { $$interval = $value; }
        $date = mktime( $base_hour + $hours, $base_minute + $minutes, $base_second + $seconds, $base_month + $months, $base_day + $days, $base_year + $years);
        return $this->str_date( $date );
	}

	function get_diff( $date2 ) {
        if ( is_string($date2) ) { $date2 = $this->parse_date( $date2 ); }

        /*if ($date2 > $this->date) {
            $diff = $date2 - $this->date;
        } else {
            $diff = $this->date - $date2;
        }*/
		$diff = $date2 - $this->date;

        $diffarr['years'] = $this->extract_from_seconds($diff, "years");
        $diffarr['months'] = $this->extract_from_seconds($diff, "months");
        $diffarr['days'] = $this->extract_from_seconds($diff, "days");
        $diffarr['hours'] = $this->extract_from_seconds($diff, "hours");
        $diffarr['minutes'] = $this->extract_from_seconds($diff, "minutes");
        $diffarr['seconds'] = $this->extract_from_seconds($diff, "seconds");

        return $diffarr;
	}

	function is_leap_year() {
		if ( is_null( $this->date) ) { return false; }
		$year = date('Y', $this->date);
		if ( function_exists('mcal_is_leap_year') ) { return mcal_is_leap_year( $year ); }
		if ( $year % 4 == 0 ) {
			if ( $year % 100 == 0 ) {
				if ( $year % 200 == 0 ) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	private function str_date( $date ) {
		if ( is_null($date) ) { return null; };
		switch ( $this->mode ) {
			case self::mode_time:
				$str_date = date("H:i:s", $date);
				break;
			case self::mode_date:
				$str_date = date("Y-m-d", $date);
				break;
			default:
				$str_date = date("Y-m-d H:i:s", $date);
		}
		return $str_date;
	}

	private function parse_date( $str ) {
		if ( is_null($str) ) { return null; }
		$date = array( 'year' => 0,	'month' => 0, 'day' => 0, 'hour' => 0, 'minute' => 0, 'second' => 0 );
		$str .= "";
		$str = preg_replace("#\s#", "", $str);
		$str = preg_replace("#\:#", "", $str);
		$str = preg_replace("#\.#", "", $str);
		$str = preg_replace("#\-#", "", $str);
		$str = preg_replace("#\/#", "", $str);
		if ( $this->mode != self::mode_time ) {
			$date['year']   = (strlen($str) == 6) ? substr($str,0,2) : substr($str,0,4);
			$date['month']  = (strlen($str) == 6) ? substr($str,2,2) : substr($str,4,2);
			$date['day']    = (strlen($str) == 6) ? substr($str,4,2) : substr($str,6,2);
			$date['hour']   = (strlen($str) < 12) ? 0 : substr($str, 8,2);
			$date['minute'] = (strlen($str) < 12) ? 0 : substr($str, 10,2);
			$date['second'] = (strlen($str) < 14) ? 0 : substr($str, 12,2);
		} else {
			$date['second'] = (strlen($str) == 6) ? substr($str, 4,2) : 0;
			$date['hour']   = (strlen($str) >= 4) ? substr($str, 0,2) : 0;
			$date['minute'] = (strlen($str) >= 4) ? substr($str, 2,2) : 0;
		}
		$parse_date = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
		return $parse_date;
	}

	private function extract_from_seconds(&$seconds, $interval) {
	    switch ($interval) {
			case "minutes": case "minute": case "i":
			    $value   = bcdiv($seconds,60);
			    $seconds = bcmod($seconds,60);
			    break;
			case "hours": case "H": case "h": case "hour":
			    $value   = bcdiv($seconds,3600);
			    $seconds = bcmod($seconds,3600);
			    break;
			case "days": case "D": case "d": case "day":
			    $value   = bcdiv($seconds,3600*24);
			    $seconds = bcmod($seconds,3600*24);
			    break;
			case "months": case "m": case "n": case "month":
			    $value   = bcdiv($seconds,3600*24*30);
			    $seconds = bcmod($seconds,3600*24*30);
			    break;
			case "weeks": case "W": case "w": case "week":
			    $value   = bcdiv($seconds,3600*24*7);
			    $seconds = bcmod($seconds,3600*24*7);
			    break;
			case "years": case "year": case "y": case "Y":
			    $value   = bcdiv($seconds,3600*24*365);
			    $seconds = bcmod($seconds,3600*24*365);
			    break;
			default:
			    $value = $seconds;
	    }
    	return $value;
    }

}

?>
