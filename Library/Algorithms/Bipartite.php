<?php

class Bipartite extends AlgorithmBase{
	public $graph;
	
	public function __construct(){
		$this->setDescription('Separate the graph nodes into two groups.');
	}
	
	public function Run(){
		$this->graph = $this->graph['inputGraph'];
		
		$bipartite = true;
		foreach(array_keys($this->graph->vertexes) as $v){
			if (!$this->groupNodes($v))
				$bipartite = false;
		}
		
		if ($bipartite)
			$result = $this->graph;
		else
			$result = 0;

		$this->setResult(array('result' => $result));
	}
	
	private function groupNodes($v){
		if (!isset($this->graph->vertexes[$v]['group']))
				$this->graph->vertexes[$v]['group'] = 1;
		
		if (isset($this->graph->adjacency[$v])){
			foreach(array_keys($this->graph->adjacency[$v]) as $u){
				if (!isset($this->graph->vertexes[$u]['group'])){
					$this->graph->vertexes[$u]['group'] = $this->graph->vertexes[$v]['group'] * -1;
					return $this->groupNodes($u);
				}elseif ($this->graph->vertexes[$u]['group'] != $this->graph->vertexes[$v]['group'] * -1){
					return false;
				}
			}
		}
		return true;
	}
	
	public function Info(){
		$this->setTitle('Bipartite Algorithm');
		$this->setInputInfo(array(
			"inputGraph" => "Graph."
		));
		$this->setResultInfo(array(
			"result" => "Grouped graph or 0 if not bipartite."
		));
	} 
}
