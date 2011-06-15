<?php
/**
 * LITE FRAMEWORK v. 1.1
 * @name lite.framework
 * @package framework
 * Inicio del Framework (bootstrap)
 * @author DSD
 * @version 1.1
 */

define ( 'DIR_FRAMEWORK' , dirname(__FILE__) );
define ( 'DIR_LIBRARIES' , DIR_BASE.DS.'libraries' );
define ( 'DIR_COMPONENT' , DIR_BASE.DS.'components' );

require_once( DIR_FRAMEWORK.DS.'application.php' );

$f = new Application;
?>