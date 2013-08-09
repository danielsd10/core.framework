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
	function generate_seo_link($input,$replace = '-',$remove_words = true,$words_array = array()){
		$input=$this->remove_accent($input);
		$return = trim(ereg_replace(' +',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));
		if($remove_words) { $return = $this->remove_words($return,$replace,$words_array); }
		return str_replace(' ',$replace,$return);
	}
	function remove_words($input,$replace,$words_array = array(),$unique_words = true){
		$input_array = explode(' ',$input);
		$return = array();
		foreach($input_array as $word){
			if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true)){
				$return[] = $word;
			}
		}
		return implode($replace,$return);
	}
	function remove_accent($str){
		$a = array('À', '�?', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', '�?', 'Î', '�?', '�?', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', '�?', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', '�?', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', '�?', 'Ď', '�?', '�?', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', '�?', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', '�?', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', '�?', 'Ŏ', '�?', '�?', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', '�?', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', '�?', 'ǎ', '�?', '�?', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
		$b = array('A','A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		return str_replace($a, $b, $str);
	}
}
?>