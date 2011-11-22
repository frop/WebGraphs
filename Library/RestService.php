<?php
class RestService{
	private $_url;
	private $_requestMethod;
	private $_arg;
	
	private $_requestResource;
	
	private $_className;
	private $_classObj;
	private $_input;
	private $_resObj;
	
	private $_algorithm;
	
	private $_graphId;
	
	private $_format;
	
	public function __construct(){
		$this->_url = $_SERVER['REQUEST_URI'];
		$this->_requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
				
		$this->_arg = array();
		$this->_arg['POST'] = $_POST;
		$this->_arg['GET'] = $_GET;
		parse_str(file_get_contents('php://input'), $this->_arg['PUT']);
		parse_str(file_get_contents('php://input'), $this->_arg['DELETE']);
		
		$this->parseUrl();
		$this->parseInput();
	}
	
	public function getUrl(){
		return $this->_url;
	}
	
	public function getRequestMethod(){
		return $this->_requestMethod;
	}
	
	public function getArg($metodo = NULL){
		if($metodo){
			return $this->_arg[$metodo];
		}
		return $this->_arg;
	}
	
	public function setClassObj($classObj){
		$this->_classObj = $classObj;
	}
	
	public function getClassObj(){
		return $this->_classObj;
	}
	
	public function setClassName($className){
		$this->_className = $className;
	}
	
	public function getClassName(){
		return $this->_className;
	}
	
	public function parseUrl(){
//		$resourcePattern = '/server\/([a-z0-9.]+)\/?[0-9a-z.]*$/';
		$resourcePattern = '/\/([a-z0-9]+)[\.\/]/';
		preg_match($resourcePattern, $this->_url, $resMatches);

		switch ($resMatches[1]){
			case 'graph':
				$this->_requestResource = 'Graph';
				if ($this->_requestMethod == 'GET'){
					$graphPattern = '/graph\/([0-9]+)\.(xml|json)$/';
					preg_match($graphPattern, $this->_url, $graphMatches);
					list(, $graphId, $format) = $graphMatches;
					$this->_graphId = $graphId;
				}elseif ($this->_requestMethod == 'POST'){
					$graphPattern = '/\.(xml|json)$/';
					preg_match($graphPattern, $this->_url, $graphMatches);
					list(, $format) = $graphMatches;
					
					require 'Resources/Graph.php';
					$graphResource = new Graph;
					$graphResource->create($_POST['graph'], $format);
					exit;
				}
				echo 'GRAPH RESOURCE';
				break;
			case 'result':
				echo 'RESULT RESOURCE';
				$this->_requestResource = 'Result';
				$id = '';
				break;
			default:
				$this->_requestResource = 'Algorithm';
				
				$algPattern = '/\/([a-z0-9]+)\.(xml|json)/';
				preg_match($algPattern, $this->_url, $algMatches);
				
				list($void, $this->_algorithm, $this->_format) = $algMatches;
				list($void, $this->_input['algorithm'], $this->_input['format']) = $algMatches; 
		}
		
	}

	public function parseInput(){
		$arg = $this->_arg[$this->_requestMethod];
		
		switch($this->_format){
			case 'xml':
				echo 'XML';
				break;
			case 'json':
				$array = json_decode($arg['input'], true);
				foreach ($array as $k => $v)
					$this->_input[$k] = $v;
		}
	}
	
	public function handle(){
		require 'Resources/'.$this->_requestResource.'.php';

		$methodToRun = ucfirst(strtolower($this->_requestMethod));
//		print_r($methodToRun);exit;
		try {
			$this->_resObj = new $this->_requestResource($this->_input);
			$this->_resObj->$methodToRun();
			
		    return true;
		} catch (Service_Exception_Abstract $e) {
		    die($e);
		} catch (Exception $e) {
		    die('An Unexpected Error Has Occurred');
		}
			return false;
	}
	
	public function Response(){
		$this->_resObj->Response();
	}

}
