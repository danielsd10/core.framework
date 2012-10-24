<?php
	include_once("core.framework.php");
	
	/* establecer ruta de archivo de configuración */
	$f->traceMode = Application::traceMode_off;
	$f->configFile = IndexPath.DS."core.framework.config";
	
	/* ejecución del framework */
	$f->run();
?>