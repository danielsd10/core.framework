<?php
/**
 * Controla la sesi�n
 * @name Session
 * @version 1.2
 */
class Session {
	private $id;
	
	/**
	 * @var Datastore
	 */
	static private $db;
	
	static private $session_table;
	
	public $mode;
	
	public function _open($path, $name) {
		//$this->db->connect();
		$Application = Application::getInstance();
		self::$db = $Application->datastore;
		self::$session_table = $Application->config('session', 'storage-table');
	}
	
	public function _close() {
		//$this->db->close();
	}
	
	public function _read($id) {
		//$this->db->query($sql);
		$rs = self::$db->query("select data from " . self::$session_table . " where id = " . sql::parse($id));
		if ($rs->rowCount > 0) {
			return $rs->rows[0]['data'];
		} else {
			return '';
		}
	}
	
	public function _write($id, $data) {
		$response = self::$db->execute( sprintf("replace into %s (id, access, data) values (%s, %s, %s)",
			self::$session_table, sql::parse($id), time(), sql::parse($data)));
		//$response = self::$db->execute("update " . self::$session_table . " set data = " . sql::parse($data) . " where id = " . sql::parse($id));
		return $response['success'];
	}
	
	public function _destroy($id) {
		$response = self::$db->execute("delete from " . self::$session_table . " where id = " . sql::parse($id));
		return $response['success'];
	}
	
	public function _gc($max) {
		$old = time() - $max;
		$response = self::$db->execute("delete from " . self::$session_table . " where access < " . sql::parse($old));
		return $response['success'];
	}
	
	public function start() {
		session_cache_limiter('none');
		session_start();
		$this->id = session_id();
		if (! isset($_SESSION['valid']) ) { $_SESSION['valid'] = false; }
	}
	
	public function end() {
		session_unset();
		session_destroy();
	}
	
	public function __get($property) {
		if (! array_key_exists($property, $_SESSION) ) { throw Application::Exception('Ses001', array($property)); }
		return $_SESSION[$property];
	}
	
	public function __set($property, $value) {
		if (is_null($value)) {
			unset( $_SESSION[$property] );
		} else {
			$_SESSION[$property] = $value;
		}
	}
}
?>