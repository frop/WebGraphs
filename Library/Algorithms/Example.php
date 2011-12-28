<?php

class Example extends AlgorithmBase{
	public function __construct(){
		$this->setDescription('algoritmo teste');
	}
	
	public function Run(){
		$result = array('k' => 12, 'tree' => $this->graph['graph']);
		$this->setResult($result);
	}
	
	public function Info(){
		echo $this->result;
		echo json_encode($this);
	} 
}
