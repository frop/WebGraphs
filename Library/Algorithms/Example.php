<?php

class Example extends AlgorithmBase{
	public function __construct(){
		$this->setDescription('algoritmo teste');
	}
	
	public function Run(){
		$this->setResult(TRUE, 'boolean'); // transformar em constante
	}
	
	public function Info(){
		echo $this->result;
		echo json_encode($this);
	} 
}
