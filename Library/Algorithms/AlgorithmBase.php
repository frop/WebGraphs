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
	public $description;
	public $param;
	public $graph;
	private $result;

	public function __construct($data){

	}

	public function setDescription($desc){
		$this->description = $desc;
	}

	public function getDescription(){
		return $this->description;
	}

	function setGraphsList($g){
		$this->graph = $g;
	}

	function setParamsList($p){
		$this->param = $p;
	}

	// only accepts numbers and strings
	public function setResult($result){
		if (!is_array($result))
			$this->result = array('result' => $result);
		else{
			foreach($result as $key => $val){
				if (is_object($val)){
					$this->result[$key] = $val->save();
				}elseif (is_int($val) || ((string)(int)$val) === $val || is_string($val)){
					$this->result[$key] = $val;
				}
			}
		}
	}

	public function getResult(){
//		if ($this->resultType == '')
		return $this->result;
	}
}

