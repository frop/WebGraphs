<?php

class Graph{
	/**
	 * 
	 * Cria um recurso do tipo 'graph'
	 * @param $graph array contendo o grafo
	 */
	function Post($graph, $format){
		switch($format){
			case 'xml':
				break;
			case 'json':
				$graph = json_decode($graph, true);
		}
			
		$graphId = sprintf("%06d", rand(1, 1000));
		$jsonFile = fopen('Data/Graphs/'.$graphId.'.json', 'w');
		if (!$jsonFile){
			echo 'erro file';
			exit;
		}
		$graphJson = json_encode($graph);
		fprintf($jsonFile, "%s\n", $graphJson);
	}
	
	function Get($id, $format){
		$graphFile = 'Data/Graphs/'.$id.'.'.$format;
		
		echo file_get_contents($graphFile);
	}
}
