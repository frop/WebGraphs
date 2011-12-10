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

					$this->_input['format'] = $format;
					$this->_input['id'] = $graphId;

				}elseif ($this->_requestMethod == 'POST'){
					$graphPattern = '/\.(xml|json)$/';
					preg_match($graphPattern, $this->_url, $graphMatches);
					list(, $format) = $graphMatches;

					$this->_input['format'] = $format;
					$this->_input['graph'] = $this->_arg['POST']['graph'];
				}
				break;
			case 'result':
				$this->_requestResource = 'Result';
				if ($this->_requestMethod == 'GET'){
					$resultPattern = '/result\/([0-9]+)\.(xml|json)$/';
					preg_match($resultPattern, $this->_url, $resultMatches);
					list(, $resultId, $format) = $resultMatches;

					$this->_input['format'] = $format;
					$this->_input['id'] = $resultId;
				}
				break;
			default:
				$this->_requestResource = 'Algorithm';
				
				$algPattern = '/\/([a-z0-9]+)\.(xml|json)/';
				preg_match($algPattern, $this->_url, $algMatches);
				
				list(, $this->_algorithm, $this->_format) = $algMatches;
				list(, $this->_input['algorithm'], $this->_input['format']) = $algMatches; 
				$this->parseInput();
		}
		
	}

	public function parseInput(){
		$arg = $this->_arg[$this->_requestMethod];

		if (array_key_exists('input', $arg)){
			switch($this->_format){
				case 'xml':
					echo 'XML';
					break;
				case 'json':
					$array = json_decode($arg, true);
					foreach ($array as $k => $v)
						$this->_input[$k] = $v;
			}
		}
	}
	
	public function handle(){
		require 'Resources/'.$this->_requestResource.'.php';

		$methodToRun = ucfirst(strtolower($this->_requestMethod));
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
