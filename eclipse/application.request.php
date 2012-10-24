<?php
class Request {
	/**
	 * nombre del servidor
	 * @var string
	 */
	public $host;
	
	/**
	 * puerto (80 por defecto)
	 * @var int
	 */
	public $port;
	
	/**
	 * ra�z de la aplicaci�n
	 * @var string
	 */
	public $root;
	
	/**
	 * m�todo o procedimiento que se est� solicitando
	 * @var string
	 */
	public $call;
	
	/**
	 * m�todo HTTP bajo el cual se hace la solicitud
	 * @var string
	 */
	public $method;
	
	/**
	 * datos enviados
	 * @var mixed
	 */
	public $data;
}
?>