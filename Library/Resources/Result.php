<?php

class Result{
	
	function Get(){
		$resultFile = 'Data/Results/'.$this->_resultId.'.'.$this->_format;
		
		echo file_get_contents($resultFile);
	}
}
