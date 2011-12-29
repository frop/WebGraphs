<?php
/*
$params
$grafos
$resultado
function salvarResultado
if grafo: grafoResultID = grafo->create, this->result = grafoResultID (vetor)
*/
class AlgorithmBase{
	public $id;

	public $title;
	public $description;
	public $inputInfo;
	public $resultInfo;

	public $param;
	public $graph;
	private $result;

	public function __construct($data){

	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setDescription($desc){
		$this->description = $desc;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setInputInfo($info){
		if (!is_array($info))
			$info = array("graph" => $info);

		$this->inputInfo = $info;
	}

	public function getInputInfo(){
		return $this->inputInfo;
	}

	public function setResultInfo($info){
		if (!is_array($info))
			$info = array("result" => $info);

		$this->resultInfo = $info;
	}

	public function getResultInfo(){
		return $this->resultInfo;
	}

	public function setGraphsList($g){
		$this->graph = $g;
	}

	public function setParamsList($p){
		$this->param = $p;
	}

	public function setResult($result){
		if (!is_array($result))
			$this->result = array('result' => $result);
		else{
			$this->result = $this->sanitizeResult($result);
		}
	}

	public function getResult(){
		return $this->result;
	}

	private function sanitizeResult($resultArray){
		foreach($resultArray as $key => $val){
			if (is_object($val)){
				$result[$key] = $val->save();
			}elseif (is_int($val) || ((string)(int)$val) === $val || is_string($val)){
				$result[$key] = $val;
			}elseif (is_array($val)){
				$result[$key] = $this->sanitizeResult($val);
			}
		}
		return $result;
	}
}

