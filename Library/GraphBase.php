<?php

/**
{
  "vertexes": {
    "g1": {"color": "red"}, 
    "g2": {"color": "blue"}, 
    "g3": {"color": "green"}
  },
  "adjacency": {
    "g1": {
	"g2": {"weight": 0},
	"g3": {"weight": 0}
    }, 
    "g2": {
	"g1": {"weight": 0},
	"g3": {"weight": 0}
    }, 
    "g3": {
	"g1": {"weight": 0}, 
	"g2": {"weight": 0}
    }
  }
}

{
  "vertexes": ["g1", "g2", "g3"],
  "adjacency": {
    "g1": {
	"g2": {"weight": 0},
	"g3": {"weight": 0}
    }, 
    "g2": {
	"g1": {"weight": 0},
	"g3": {"weight": 0}
    }, 
    "g3": {
	"g1": {"weight": 0}, 
	"g2": {"weight": 0}
    }
  }
}
*/


/*
Array
(
    [id] => 123123
    [vertexes] => Array
        (
            [g1] => Array
                (
                    [color] => red
                )

            [g2] => Array
                (
                    [color] => blue
                )

            [g3] => Array
                (
                    [color] => green
                )

        )

    [adjacency] => Array
        (
            [g1] => Array
                (
                    [0] => g2
                    [1] => g3
                )

            [g2] => Array
                (
                    [g1] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                    [g3] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                )

            [g3] => Array
                (
                    [g1] => Array
                        (
                            [data] => Array
                                (
                                    [weight] => 0
                                )

                        )

                    [g2] => Array
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

	public function loadFromArray($array){
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
	
	public function getVertices(){
		return $this->vertices;
	}
	
	public function setArestas($listaArestas){
		$this->arestas = new Arestas;
		$this->arestas->setArestas($listaArestas);
	}
	
	public function getArestas(){
		return $this->arestas;
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
		$graphJson = json_encode($this);
		fprintf($jsonFile, "%s\n", $graphJson);

		return $this->id;
	}
}
