<?php

class Graph{
	private $_id;
	private $_graph;
	private $_format;

	function __construct($params){
		$this->_id = isset($params['id'])? $params['id'] : null;
		$this->_graph = isset($params['graph'])? $params['graph'] : null;

		$this->_format = $params['format'];

	}

	function Get(){
		$graphFile = GRAPH_DATA_DIR.'/'.$this->_id.'.'.$this->_format;
		$this->_graph = file_get_contents($graphFile);
	}

	/**
	 * 
	 * Cria um recurso do tipo 'graph'
	 * @param $graph array contendo o grafo
	 */
	function Post(){
		switch($this->_format){
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

	function Response(){
		echo $this->_graph;
	}
}
