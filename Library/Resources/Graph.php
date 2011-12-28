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
		if (is_file($graphFile))
			$this->_response = file_get_contents($graphFile);
		else
			$this->_response = array("error" => "Graph not found.");
	}

	function Post(){
		require 'GraphBase.php';

		$graph = new GraphBase(json_decode($this->_graph, true));
		if (!$graph->save()){
			$this->_response = array("error" => "Error while saving graph file.");
			return;
		}

		$this->_response = array("graphId" => $graph->getId());
	}

	function Response(){
		return $this->_response;
	}
}
