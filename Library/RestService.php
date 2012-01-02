<?php
class RestService{
	private $_url;
	private $_requestMethod;
	private $_arg;
	
	private $_requestResource;

	private $_input;
	private $_resObj;
	
	private $_algorithm;
	
	private $_graphId;
	
	private $_format;
	
	private $_error = false;
	
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
	
	public function parseUrl(){
		$resourcePattern = '/\/([a-z][a-z0-9_]+)[\.\/]/';
		if (!preg_match($resourcePattern, $this->_url, $resMatches)){
			$this->_error = 'You must especify a resource.';
			return false;
		}

		switch ($resMatches[1]){
			case 'graph':
				$this->_requestResource = 'Graph';
				if ($this->_requestMethod == 'GET'){
					$graphPattern = '/graph\/([0-9]+)\.(xml|json)$/';
					if(preg_match($graphPattern, $this->_url, $graphMatches)){
						list(, $graphId, $format) = $graphMatches;

						$this->_input['format'] = $format;
						$this->_input['id'] = $graphId;
					}else{
						$this->_error = 'Impossible to determine graph or format requested.';
					}

				}elseif ($this->_requestMethod == 'POST'){
					$graphPattern = '/\.(xml|json)$/';
					if(preg_match($graphPattern, $this->_url, $graphMatches)){
						list(, $format) = $graphMatches;

						$this->_input['format'] = $format;
						$this->_input['graph'] = $this->_arg['POST']['graph'];
					}else{
						$this->_error = 'Impossible to determine format requested.';
					}
				}else{
					$this->_error = 'Invalid request for GRAPH resource.';
				}
				break;
			case 'result':
				$this->_requestResource = 'Result';
				if ($this->_requestMethod == 'GET'){
					$resultPattern = '/result\/([0-9]+)\.(xml|json)$/';
					if(preg_match($resultPattern, $this->_url, $resultMatches)){
						list(, $resultId, $format) = $resultMatches;

						$this->_input['format'] = $format;
						$this->_input['id'] = $resultId;
					}else{
						$this->_error = 'Impossible to determine result or format requested.';
					}
				}else{
					$this->_error = 'Invalid request for RESULT resource.';
				}
				break;
			default:
				$this->_requestResource = 'Algorithm';
				$algPattern = '/\/([a-z][a-z0-9_]+)\.(xml|json)/';
				
				if (preg_match($algPattern, $this->_url, $algMatches)){
					list(, $this->_input['algorithm'], $this->_input['format']) = $algMatches;
					if ($this->_requestMethod == 'POST'){
						$this->_getList('graph');
						$this->_getList('param');
					}elseif ($this->_requestMethod != 'GET'){
						$this->_error = 'Invalid request for ALGORITHM resource.';
					}
				}else{
					$this->_error = 'Impossible to determine algorithm or format requested.';
				}
		}
		
	}

	private function _getList($listName){
		if (isset($this->_arg['POST'][$listName])){
			$list = $this->_arg['POST'][$listName];
			if (!is_array($list))
				$list = array($listName => $list);
		}else
			$list = '';

		$this->_input[$listName] = $list;
	}

	public function parseInput(){
		$arg = $this->_arg[$this->_requestMethod];

		if (array_key_exists('input', $arg)){
			switch($this->_input['format']){
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
		if ($this->_error === false){
			if (!is_file(LIBRARY_DIR.'/Resources/'.$this->_requestResource.'.php')){
				$this->_error = $this->_requestResource.' algorithm was not found in the server.';
				return false;
			}
			
			require 'Resources/'.$this->_requestResource.'.php';

			$methodToRun = ucfirst(strtolower($this->_requestMethod));
			try {
				$this->_resObj = new $this->_requestResource($this->_input);
				$this->_resObj->$methodToRun();
			
				return true;
			} catch (Service_Exception_Abstract $e) {
				$this->_error = $e;
			} catch (Exception $e) {
				$this->_error = 'An unexpected error has occurred';
			}
		}
	}
	
	public function SendResponse($debug = FALSE){
		if ($this->_error === false)
			$response = $this->_resObj->Response();
		else
			$response = array("error" => $this->_error);

		if (is_array($response)){ // ainda nao esta no formato que queremos
			switch($this->_input['format']){
				case 'xml':
					echo 'XML';
					break;
				case 'json':
				default:
					$response = json_encode($response);
			}
		}

		if ($debug){
			header('Content-type: text/html');
			echo "<script>console.log($response)</script>";
		} else {
			echo $response;
		}
	}

}
