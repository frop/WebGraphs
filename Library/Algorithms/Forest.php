<?php

class Forest extends AlgorithmBase{
	public function __construct(){
		$this->setDescription('Get tree set from a given disconnected graph.');
	}
	
	public function Run(){
		$graph = $this->graph['inputGraph'];
		$treeSet = array();
		$treeIndex = -1;

		$vertexes = $graph->vertexes;

		foreach(array_keys($vertexes) as $v){
			if (!isset($vertexes[$v]['mark'])){
				$vertexes[$v]['mark'] = ++$treeIndex;
				$treeSet[$treeIndex] = new GraphBase();
				$treeSet[$treeIndex]->createVertex($v);
			}

			$tree = $treeSet[$vertexes[$v]['mark']];
			
			foreach(array_keys($graph->adjacency[$v]) as $n){
				if (!isset($vertexes[$n]['mark'])){
					$tree->createVertex($n);
					$tree->createEdge($v, $n);
					$vertexes[$n]['mark'] = $vertexes[$v]['mark'];
				}
			}
		}

		$result = array('count' => count($treeSet), 'treeSet' => $treeSet);
		$this->setResult($result);
	}
	
	public function Info(){
		$this->setTitle('Forest Algorithm');
		$this->setInputInfo(array(
			"inputGraph" => "Graph from which the forest will be derived."
		));
		$this->setResultInfo(array(
			"count" => "Number of trees in the forest.",
			"treeSet" => "List of graph identifiers representing each derived tree."
		));
	} 
}
