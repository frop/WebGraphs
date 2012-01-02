<?php

/**
{
  "vertexes": {
    "v1": {"color": "red"}, 
    "v2": {"color": "blue"}, 
    "v3": {"color": "green"}
  },
  "adjacency": {
    "v1": {
	"v2": {"weight": 0},
	"v3": {"weight": 0}
    }, 
    "v2": {
	"v1": {"weight": 0},
	"v3": {"weight": 0}
    }, 
    "v3": {
	"v1": {"weight": 0}, 
	"v2": {"weight": 0}
    }
  }
}

{
  "vertexes": ["v1", "v2", "v3"],
  "adjacency": {
    "v1": ["v2", "v3"], 
    "v2": ["v1", "v3"],
    "v3": ["v1", "v2"]
  }
}
*/


/*
Array
(
    [id] => 123123
    [vertexes] => Array
        (
            [v1] => Array
                (
                    [color] => red
                )

            [v2] => Array
                (
                    [color] => blue
                )

            [v3] => Array
                (
                    [color] => green
                )

        )

    [adjacency] => Array
        (
            [v1] => Array
                (
                    [0] => v2
                    [1] => v3
                )

            [v2] => Array
                (
                    [v1] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                    [v3] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                )

            [v3] => Array
                (
                    [v1] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                    [v2] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                )

        )

)
*/

class GraphBase{
	public $id;
	public $vertexes;
	public $adjacency;
	
	public function __construct($grafo = NULL){
		$this->id = 0;
		if ($grafo && is_array($grafo)){
			$this->loadFromArray($grafo);
		}
	}

	private function loadFromArray($array){
		if (isset($array['id']))
			$this->id = $array['id'];

		$vertexesList = $array['vertexes'];
		foreach($vertexesList as $id => $data){
			if (is_array($data)){
				$this->vertexes[$id] = $data;
			}else{
				$this->vertexes[$data] = array("index" => $id);
			}
		}

		$adjacencyList = $array['adjacency'];
		foreach($adjacencyList as $vertFrom => $vertsTo){
			foreach($vertsTo as $v => $data){
				if (is_array($data)){
					$this->adjacency[$vertFrom][$v] = $data;
				}else{
					$this->adjacency[$vertFrom][$data] = array("weight" => 0);
				}
			}
		}
	}

	public function createVertex($v, $data = NULL){
		if ($data){
			if (!is_array($data)){
				$data = array("data" => $data);
			}
		}else{
			$data = array("index" => count($this->vertexes));
		}
		$this->vertexes[$v] = $data;
	}

	public function deleteVertex($v){
		if (!isset($this->vertexes[$v]))
			return;

		unset($this->vertexes[$v]);
		foreach(array_keys($this->adjacency[$v]) as $u){
			unset($this->adjacency[$u][$v]);
			if (!count($this->adjacency[$u])){
				unset($this->adjacency[$u]);
			}
		}
		unset($this->adjacency[$v]);
	}

	public function createEdge($u, $v, $data = NULL){
		if(!isset($this->vertexes[$u]) || !isset($this->vertexes[$v]) || isset($this->adjacency[$u][$v]))
			return;

		if ($data){
			if (!is_array($data)){
				$data = array("weight" => $data);
			}
		}else{
			$data = array("weight" => 0);
		}

		$this->adjacency[$u][$v] = $data;
		$this->adjacency[$v][$u] = $data;
	}

	public function deleteEdge($u, $v){
		unset($this->adjacency[$u][$v]);
		if (!count($this->adjacency[$u])){
			unset($this->adjacency[$u]);
		}

		unset($this->adjacency[$v][$u]);
		if (!count($this->adjacency[$v])){
			unset($this->adjacency[$v]);
		}
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function save(){
		do{
			$graphId = sprintf("%06d", rand(1, 1000000));
		}while(is_file(GRAPH_DATA_DIR.'/'.$graphId.'.json'));

		$this->id = $graphId;

		$jsonFile = fopen(GRAPH_DATA_DIR.'/'.$this->id.'.json', 'w');
		if (!$jsonFile){
			return 0;
		}
		$graphJson = json_encode($this, JSON_FORCE_OBJECT);
		fprintf($jsonFile, "%s\n", $graphJson);

		return $this->id;
	}
}
