<?php
class Algorithm{
	private $_format;
	private $_algorithm;
	private $_graphsId;
	private $_params;
	private $_algObject;
	private $_resultId;
//	private $_graphsData;

	function __construct($data){
		
		$this->setFormat($data['format']);
		$this->setAlgorithm($data['algorithm']);
		$this->setGraphsId($data['graphIds']);
		$this->setParams($data['parameters']);
		
	}

	function Get(){
		# RODAR <ALG>->Info();
	}
	
	function Post(){
		require ALGORITHM_DIR . DIRECTORY_SEPARATOR . $this->_algorithm . ".php";
		$graphsData = array();

		foreach($this->_graphsId as $gId){
			$graphsData[] = new Grafo(json_decode(file_get_contents(GRAPH_DATA_DIR . DIRECTORY_SEPARATOR . "$gId.json"), TRUE));
		}
		
		$this->_algObject = new $this->_algorithm;
		$this->_algObject->setParams($this->_params);
		$this->_algObject->setGraphs($graphsData);
		$this->_algObject->Run();
		
		$this->_saveResult();
	}
	
	function Response(){
		$response = array('resultId' => $this->_resultId);
		
		switch ($this->_format){
			case 'xml':
				break;
			case 'json':
				return json_encode($response);
		}
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
		$this->_resultId = sprintf("%06d", rand(1,999999));
		$this->_algObject->setGraphs($this->_graphsId);
//		$this->result2xml();
		$this->result2json();
	}
	
	private function result2xml(){
		fprintf($xmlFile, "%s\n", $xml);
	}
	
	private function result2json(){
		$jsonFile = fopen(RESULT_DATA_DIR . DIRECTORY_SEPARATOR . $this->_resultId . ".json", "w");
		$json = json_encode($this->_algObject);
		
		fprintf($jsonFile, "%s\n", $json);
	}
}
