<?php
class helper {
	function orderMultiDimensionalArray ($toOrderArray, $field, $inverse = false) {
	    $position = array();
	    $newRow = array();
	    foreach ($toOrderArray as $key => $row) {
	            $position[$key]  = $row[$field];
	            $newRow[$key] = $row;
	    }
	    if ($inverse) {
	        arsort($position);
	    }
	    else {
	        asort($position);
	    }
	    $returnArray = array();
	    foreach ($position as $key => $pos) {
	        $returnArray[] = $newRow[$key];
	    }
	    return $returnArray;
	}
	function paramsSearch($criterio,$campos,$operador='$or'){
		$expreg=$this->expreReg($criterio);
		$params[$operador]=array();
		foreach ($campos as $campo) {
			array_push($params[$operador], array($campo=>new MongoRegex($expreg['text'])));
		}
		return $params;
	}
	function filtrar($criterio,$data,$campos){
		$expreg=$this->expreReg($criterio);
		$text = '';//print_r($data);die();
		foreach ($campos as $campo) {
			$text.=$data[$campo].' ';
		}
		preg_match_all($expreg['text'],$text , $resp);
		if($expreg['num']==count($resp[0])){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	function expreReg($criterio){
		$text='';
		$palabras = explode(' ', $criterio);
		$tot_pal=0;
		foreach ($palabras as $palabra) {
			if($palabra!=''){
				$text.=$palabra.'|';
				$tot_pal++;
			}
		}
		$text=substr($text,0,-1);
		$expreg['text']='/('.$text.')/i';
		$expreg['num']=$tot_pal;
		
		return $expreg;
	}
	/*
	'dataY'  = Data Vertical,
	'labelY' = Etiqueta Vertical,
	'dataX'  = Data Horizontal,
	'labelX' = Etiqueta Horizontal,
	'title'  = Título de gráfico,
	'path'   = Ruta de la imagen
	 * */
	function printGraphBar($data){
		global $f;
		$f->library("graphics");
		// We need some data
		$datay=$data['dataY'];
		$legend = $data['dataX'];
		
		// Set up the graph
		$graph = new Graph(800,400,"auto");
		$graph->img->SetMargin(60,30,30,40);
		$graph->graph_theme = null;
		$graph->SetScale("textlin");
		$graph->SetShadow();
		
		// Create the bar pot
		$bplot = new BarPlot($datay);
		$bplot->SetWidth(0.6);
		
		// Set up color for gradient fill style
		$bplot->SetFillColor("orange");
		$bplot->value->Show();
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$bplot->value->SetAngle(45);
		$bplot->value->SetFormat('%0.1f');
		$graph->Add($bplot);
		
		// Set up the title for the graph
		$graph->title->Set($data['title']);
		$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
		
		// Set up font for axis
		$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
		$graph->yaxis->title->Set($data['labelY']);
		$graph->yaxis->title->SetFont(FF_VERDANA,FS_NORMAL,10);
		
		// Set up X-axis title (color &amp; font)
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
		$graph->xaxis->title->Set($data['labelX']);
		$graph->xaxis->title->SetFont(FF_VERDANA,FS_NORMAL,10);
		$graph->xaxis->SetTickLabels($legend);
		//$graph->xaxis->SetLabelAlign('right','center','right');
		
		// Finally send the graph to the browser
		$graph->Stroke(_IMG_HANDLER);
		$graph->img->Stream($data['path']);
	}
	function printGraphPie($data){
		global $f;
		$f->library("graphics");
		$info=$data['dataY'];
		// Create the Pie Graph.
		$graph = new PieGraph(550,450);
		$graph->graph_theme = null;
		$graph->SetShadow();
		// Set A title for the plot
		$graph->title->Set($data['title']);
		$graph->SetBox(true);
		// Create
		$p1 = new PiePlot($info);
		$graph->Add($p1);
		$p1->ShowBorder();
		$p1->SetColor('black');
		if(isset($data['color'])) $p1->SetSliceColors($data['color']);
		//$graph->legend->SetPos(0.5,0.98,'center','bottom');
		$p1->SetLegends($data['legend']);
		$graph->Stroke(_IMG_HANDLER);
		$graph->img->Stream($data['path']);
	}
}
?>