<?php

class Result{
	private $_id;
	private $_format;

	function __construct($params){
		$this->_id = isset($params['id'])? $params['id'] : null;
		$this->_format = $params['format'];
	}

	function Get(){
		$resultFile = RESULT_DATA_DIR.'/'.$this->_id.'.'.$this->_format;
		$this->_response = file_get_contents($resultFile);
	}

	function Post(){
		$this->_response = 'Invalid method';
	}

	function Response(){
		return $this->_response;
	}
}
