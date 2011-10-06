<?php

/**
 * 
 * "id":"G",
 * "tipo":"grafo",
 * "vertices":{
 *	"quantidade":3,
 *	"vertice":[
 *	{ "id":"g1" },
 *	{ "id":"g2" },
 *	{ "id":"g3" }
 *	]
 * },
 * "arestas":{
 *	"quantidade":3,
 *	"aresta":[
 *	{ "id": "a1", "origem":"g1", "destino":"g2", "peso":0 },
 *	{ "id": "a2", "origem":"g1", "destino":"g3", "peso":3 },
 *	{ "id": "a3", "origem":"g2", "destino":"g3", "peso":-1 }
 *	]
 * }
 * 
 */

class Vertices{
	public $quantidade;
	public $vertice;
	
	public function __construct(){
		$this->quantidade = 0;
		$this->vertice = array();
	}
	
	public function setVertices($listaVertices){
		foreach($listaVertices as $vertices)
			$this->criaVertice($vertices);
	}
	
	public function criaVertice($vertice){
		$this->quantidade++;
		array_push($this->vertice, $vertice);
	}
}

class Arestas{
	public $quantidade;
	public $aresta;
	
	public function __construct(){
		$this->quantidade = 0;
		$this->aresta = array();
	}
	
	public function setArestas($listaArestas){
		foreach($listaArestas as $aresta)
			$this->criaAresta($aresta);
	}
	
	public function criaAresta($aresta){
		$this->quantidade++;
		array_push($this->aresta, $aresta);
	}
}

class Grafo{
	public $id;
	public $tipo;
	public $vertices;
	public $arestas;
	
	public function __construct($grafo = NULL){
		if ($grafo && is_array($grafo)){
			$this->loadFromArray($grafo);
		}
	}
	
	// THROW EXCEPTIONS
	// PRECISA ID?
	// PRECISA QUANTIDADE?
	public function loadFromArray($array){
		$this->tipo = $array['tipo'];
		$this->id = $array['id'];
		
		$vertices = $array['vertices'];
		// $vertices['quantidade'];
		$this->setVertices($vertices['vertice']);
		
		$arestas = $array['arestas'];
		// $arestas['quantidade'];
		$this->setArestas($arestas['aresta']);
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	public function setVertices($listaVertices){
		$this->vertices = new Vertices;
		$this->vertices->setVertices($listaVertices);
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
}
