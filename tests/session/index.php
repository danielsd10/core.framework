<?php
	include_once("core.framework.php");
	
	/* establecer ruta de archivo de configuraci�n */
	$f->traceMode = Application::traceMode_off;
	$f->configFile = IndexPath.DS."core.framework.config";
	
	/* ejecuci�n del framework */
	$f->run();
?>