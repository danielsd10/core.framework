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
	 * raíz de la aplicación
	 * @var string
	 */
	public $root;
	
	/**
	 * método o procedimiento que se está solicitando
	 * @var string
	 */
	public $call;
	
	/**
	 * método HTTP bajo el cual se hace la solicitud
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