<?php

class Complete_graph extends AlgorithmBase{
	public function __construct(){
		$this->setDescription('Generate a general k-graph.');
	}
	
	public function Run(){
		$k = $this->param['k'];
		if ($k > 0){
			$graph = new GraphBase();
			for($i=0; $i<$k; $i++){
				$graph->createVertex($i);
				for($j=0; $j<$i; $j++){
					$graph->createEdge($j,$i);
				}
			}
			
			$this->setResult($graph);
		}
	}
	
	public function Info(){
		$this->setTitle('Generate a complete graph');
		$this->setInputInfo(array(
			"k" => "Vertexes degree."
		));
		$this->setResultInfo("K-Graph");
	} 
}
