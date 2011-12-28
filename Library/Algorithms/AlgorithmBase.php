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

	// only accepts numbers and strings
	public function setResult($result){
		if (!is_array($result))
			$this->result = array('result' => $result);
		else{
			foreach($result as $key => $val){
				if ( is_int($val) || ((string)(int)$val) === $val || is_string($val)){
					$this->result[$key] = $val;
				}elseif ((is_object($val)) && ($val instanceof 'Grafo')){
					$this->result[$key] = $val->save();
				}
			}
		}
	}

	public function getResult(){
//		if ($this->resultType == '')
		return $this->result;
	}
}

