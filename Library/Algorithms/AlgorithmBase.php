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
	public $parameters;
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

	public function setResult($result, $type){
		$this->result = $result;
		$this->resultType = $type;
	}

	public function getResult(){
//		if ($this->resultType == '')
		return $this->result;
	}
}

