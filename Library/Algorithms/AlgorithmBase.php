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

	// COLOCAR EXEMPLO EM TUDO
	
	// Título do algoritmo
	public $title;
	
	// Descrição breve do algoritmo. Além de ser usado na página de informação, é exibido no arquivo de resultado.
	public $description;
	
	// Vetor associativo com informações sobre cada dado de entrada do algoritmo. É indexado pelo nome do dado, que pode ser um grafo ou um parâmetro.
	public $inputInfo;
	
	// Semelhante ao vetor de entrada, descreve a saída gerada pelo algoritmo.
	public $resultInfo;

	// Vetor associativo que armazena os parâmetros de entrada. É indexado pelos nomes dos dados.
	public $param;
	
	// Vetor associativo que armazena os objetos da classe GraphBase, obtidos através dos identificadores passados como argumento. É indexado pelos nomes dos grafos de entada.
	public $graph;
	
	// Vetor associativo que armazena os resultados gerados pelo algoritmo. É indexado pelos nomes dos dados gerados.
	private $result;

	public function __construct($data){

	}

	// Define o título do algoritmo.
	public function setTitle($title){
		$this->title = $title;
	}

	// Obtém o título do algoritmo.
	public function getTitle(){
		return $this->title;
	}

	// Define uma descrição breve do algoritmo.
	public function setDescription($desc){
		$this->description = $desc;
	}

	// Obtém a descrição do algoritmo.
	public function getDescription(){
		return $this->description;
	}

	// Define informações sobre os parâmetros de entrada do algoritmo.
	public function setInputInfo($info){
		if (!is_array($info))
			$info = array("graph" => $info);

		$this->inputInfo = $info;
	}

	// Obtém as informações de entrada.
	public function getInputInfo(){
		return $this->inputInfo;
	}

	// Define informações sobre o resultado gerado pelo algoritmo.
	public function setResultInfo($info){
		if (!is_array($info))
			$info = array("result" => $info);

		$this->resultInfo = $info;
	}

	// Obtém as informações sobre o resultado.
	public function getResultInfo(){
		return $this->resultInfo;
	}

	// Define a lista de grafos de entrada.
	public function setGraphsList($g){
		$this->graph = $g;
	}

	// Define a lista de parâmetros.
	public function setParamsList($p){
		$this->param = $p;
	}

	// Define a lista de resultados. A função sanitizeResult() é chamada para tratar cada dado. Caso seja passado apenas um escalar, o valor é inserido em um vetor.
	public function setResult($result){
		if (!is_array($result))
			$result = array('result' => $result);
		
		$this->result = $this->sanitizeResult($result);
	}

	// Obtém a lista de resultados.
	public function getResult(){
		return $this->result;
	}

	// Função recursiva para tratar a lista de resultados. Para cada valor, verifica-se seu tipo. Caso seja um objeto da classe GraphBase, é salvo no sistema e seu identificador é usado na lista.
	private function sanitizeResult($resultArray){
		foreach($resultArray as $key => $val){
			if (is_object($val)){
				$result[$key] = $val->save();
			}elseif (is_int($val) || ((string)(int)$val) === $val || is_string($val)){
				$result[$key] = $val;
			}elseif (is_array($val)){
				$result[$key] = $this->sanitizeResult($val);
			}
		}
		return $result;
	}
}

