<?php

/*
duas listas:
graph = lista de grafos.. ex:
	graph['base'] = 149112
	graph['destino'] = 2013491
param = lista de parametros.. ex:
	param['grau'] = 2
	param['pintar'] = false
*/

class Algorithm{
	private $_format;
	private $_algorithm;
	private $_graphsId;
	private $_params;
	private $_algObject;
	private $_resultId;

	private $_algorithmFile;

	private $_result = array();

	function __construct($data){
		$this->_algorithmFile = ALGORITHM_DIR.'/'.ucfirst($this->_algorithm).'.php';

		$this->setFormat($data['format']);
		$this->setAlgorithm($data['algorithm']);

		if (isset($data['graph']))
			$this->setGraphsId($data['graph']);

		if (isset($data['param']))
			$this->setParams($data['param']);
	}

	function Get(){
		if (!is_file($this->_algorithmFile)){
			$this->_response = array('error' => 'Algorithm '.$this->_algorithm.' not found.');
			return;
		}

		require ALGORITHM_DIR.'/'.'AlgorithmBase'.'.php';
		require $this->_algorithmFile;

		$this->_algObject = new $this->_algorithm;

		$this->_algObject->Info();
		$this->_response = array(
			"title" => $this->_algObject->getTitle(),
			"description" => $this->_algObject->getDescription(),
			"input" => $this->_algObject->getInputInfo(),
			"result" => $this->_algObject->getResultInfo()
		);
	}
	
	function Post(){
		if (!is_file($this->_algorithmFile)){
			$this->_response = array('error' => 'Algorithm '.$this->_algorithm.' not found.');
			return;
		}

		require 'GraphBase.php';
		require ALGORITHM_DIR.'/'.'AlgorithmBase'.'.php';
		require $this->_algorithmFile;

		$graphsData = array();

		foreach($this->_graphsId as $key => $gId){
			$graphsData[$key] = new GraphBase(json_decode(file_get_contents(GRAPH_DATA_DIR .'/'. "$gId.json"), TRUE));
		}
		
		$this->_algObject = new $this->_algorithm;

		$this->_algObject->setGraphsList($graphsData);
		$this->_algObject->setParamsList($this->_params);
		$this->_algObject->Run();
		
		$this->_saveResult();

		$this->_response = array('resultId' => $this->_result['id']);
	}
	
	function Response(){
		return $this->_response;
	}

	function setFormat($f){
		$this->_format = $f;
	}
	
	function setAlgorithm($a){
		$this->_algorithm = $a;
	}
	
	function setGraphsId($g){
		if (is_array($g)){
			$this->_graphsId = $g;
		}else{
			$this->_graphsId = array();
			$this->_graphsId[0] = $g;
		}
	}
	
	function setParams($p){
		$this->_params = $p;
	}
	
	private function _saveResult(){
		$this->createResultId();
		$this->_result['algorithm']['name'] = $this->_algorithm;
		$this->_result['algorithm']['description'] = $this->_algObject->getDescription();
		$this->_result['graph'] = $this->_graphsId;
		$this->_result['parameter'] = $this->_params;
		$this->_result['result'] = $this->_algObject->getResult();
		
//		$this->result2xml();
		$this->result2json();
	}
	
	private function result2json(){
		$jsonFile = fopen(RESULT_DATA_DIR .'/'. $this->_result['id'] . ".json", "w");
		$json = json_encode($this->_result);
		
		fprintf($jsonFile, "%s\n", $json);
	}

	private function createResultId(){
		do{
			$resultId = sprintf("%06d", rand(1, 1000000));
		}while(is_file(RESULT_DATA_DIR.'/'.$resultId.'.json'));
		$this->_result['id'] = $resultId;
	}
}
