<?php
Class Example /* extends AlgorithmAbstract */{
	/**
	 * 
	 * Enter description here ...
	 * @var associative mixed array
	 */
	public $parameters;
	
	/**
	 * 
	 * Enter description here ...
	 * @var graph object array
	 */
	public $graphs;
	
	/**
	 * 
	 * Enter description here ...
	 * @var associative mixed array
	 */
	public $result;
	
	public function setParams($p){
		$this->parameters = $p;
	}
	
	public function setGraphs($g){
		$this->graphs = $g;
	}
	
	public function Run(){
		$this->result = TRUE;
		// $this->resultType = 'boolean' ?
	}
	
	public function Info(){
		echo $this->result;
		echo json_encode($this);
	} 
}
