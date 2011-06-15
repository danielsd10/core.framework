<?php
/**
 * FRAMEWORK v. 1.0
 * @name base
 * @package framework
 * Inicio del Framework
 * @author DSD
 * @version 1.1
 */

define ( 'DIR_FRAMEWORK' , dirname(__FILE__) );
define ( 'DIR_LIBRARIES' , DIR_BASE.DS.'libraries' );
define ( 'DIR_COMPONENT' , DIR_BASE.DS.'components' );

require_once( DIR_FRAMEWORK.DS.'application.php' );

$f = new Application;

$f->load->config();
$f->load->database();
$f->load->session();
$f->load->controller();

if ( $f->controller->component == "ac" && $f->controller->module == "start" )  {
	$f->controller->execute();
	$f->controller->output();
} elseif ( $f->controller->component == "as" && $f->controller->module == "registro" )  {
	$f->controller->execute();
	$f->controller->output();	
} elseif ( ! $f->session->is_valid() ) {
	$f->session->end();
} elseif ( $f->controller->component == "ci" && $f->controller->module == "start" ) {
	$f->controller->execute();
	$f->controller->output();
} elseif ( $f->session->user->allow_to( $f->controller->component . "/" . $f->controller->module ) ) {
	$f->controller->execute();
	$f->controller->output();
	$f->session->save();
} else {
	echo ("acceso inválido <br>");
}

?>