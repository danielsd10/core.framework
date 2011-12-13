<?php
/**
 * Manejo de bases de datos MySQL
 * @name Datastore_mysql
 * @version 1.2
 */
class Datastore_mysql extends Datastore {
	
	public function __construct( $config ) {
		/* incluir librer�a sql */
		if (! file_exists(dirname(__FILE__).DS.'sql.php')) { throw Application::Exception('Dst002'); }
		require_once('sql.php');
		
		$this->config = $config;
		try {
			$this->connectionString = sprintf("mysql:host=%s; port=%s; dbname=%s",
				isset($this->config['server']) ? $this->config['server'] : "localhost",
				isset($this->config['port']) ? $this->config['port'] : 3306,
				$this->config['database']
			);
		} catch (Exception $e) {
			throw Application::Exception('Dst003');
		}
	}
	
	public function connect() {
		if (tracing()) Trace::info("Estableciendo conexion a la Base de datos: " . $this->connectionString, 'Datastore');
		try {
			$this->cn = new PDO( $this->connectionString,
				isset($this->config['user']) ? $this->config['user'] : "root",
				isset($this->config['password']) ? $this->config['password'] : ""
			);
			$this->cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->cn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND , "SET NAMES 'ISO-8859-1'");
		} catch (PDOException $e) {
   			throw Application::Exception('Dst004', array($e->getMessage()));
		}
	}
	
	public function close() {
		unset( $this->cn );
	}
	
	public function quote($string) {
		return $this->cn->quote($string);
	}
	
	public function query($sql, $flags = 0) {
		$flags = ( $flags != 0 ) ? $flags : $this->flags;
		
		if ( $flags & Datastore::count_rows ) {
			if ( substr_count($sql, "select") ) {
				// incluir sql_calc_found_rows despues de select
				$sql = preg_replace('/select/', 'select sql_calc_found_rows ', $sql, 1);
			}
		}
		try {
			if (tracing()) Trace::table(array( array('SQL'), array($sql)), 'Datastore::query()');
			$rs = $this->cn->query($sql);
			$ds = new Dataset($rs);
		} catch (PDOException $e) {
			throw Application::Exception('Dst005', array($e->getMessage()));
   			//return false;
		}
		
		if ( $flags & Datastore::count_rows ) {
			$sql = "select found_rows() as count";
			try {
				$rs = $this->cn->query($sql);
				$ds->foundCount = $rs->fetchColumn();
				$rs->closeCursor();
			} catch (PDOException $e) {
				throw Application::Exception('Dst006', array($e->getMessage()));
			}
		}
		
		return $ds;
	}
	
	public function execute($sql, $flags = 0) {
		$flags = ( $flags != 0 ) ? $flags : $this->flags;
		
		$response = array();
		if (tracing()) Trace::table(array( array('SQL'), array($sql)), 'Datastore::execute()');
		$exec = $this->cn->exec($sql);
		if ( $exec === false ) {
			$response['success'] = false;
		} else {
			$response['success'] = true;
			$response['affectedRows'] = $exec;
			$response['lastID'] = $this->cn->lastInsertId();
		}
		
		return $response;
	}
	
	public function beginTransaction() {
		$this->cn->beginTransaction();
	}
	
	public function endTransaction($operation = Datastore::transaction_commit) {
		if ($operation == Datastore::transaction_commit) {
			$this->cn->commit();
		} elseif ($operation == Datastore::transaction_rollback) {
			$this->cn->rollBack();
		}
	}
}
?>