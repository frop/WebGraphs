<?php
/*
$params
$grafos
$resultado
function salvarResultado
if grafo: grafoResultID = grafo->create, this->result = grafoResultID (vetor)
*/
class AlgorithmBase{
	private $id;
	private $description;
	private $parameters;
	private $graph;
	private $result;

	public function __construct($data){

	}

	public function setDescription($desc){
		$this->description = $desc;
	}

	public function getDescription(){
		return $this->description;
	}
}

