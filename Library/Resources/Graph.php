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
		$this->_response = file_get_contents($graphFile);
	}

	function Post(){
		do{
			$graphId = sprintf("%06d", rand(1, 1000000));
		}while(is_file(GRAPH_DATA_DIR.'/'.$graphId.'.json'));

		switch($this->_format){
			case 'xml':
				break;
			case 'json':
				$graph = json_decode($this->_graph, true);
		}
		
		$jsonFile = fopen(GRAPH_DATA_DIR.'/'.$graphId.'.json', 'w');
		if (!$jsonFile){
			$this->_response = "Error while opening graph file";
			return;
		}
		$graphJson = json_encode($graph);
		fprintf($jsonFile, "%s\n", $graphJson);

		$this->_response = json_encode(array("graphId" => $graphId));
	}

	function Response(){
		echo $this->_response;
	}
}
