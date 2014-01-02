<?php
//(c) Andreas Galazis 2013
require_once 'Dataprocessor.php';
class CSVProxy{
	private $dataprocessor=NULL;
	function  __construct($givenURI=NULL){
		if (!is_null ($givenURI)){
	   $this->dataprocessor=new Dataprocessor($givenURI);
		}
	}
   /*abandoned this one as I will be using a custom
    ajax request but it might be usefull in the future*/
	function generateCSVResponce(){
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=table.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		if (!is_null ($this->dataprocessor)){
			echo $this->dataprocessor->getCSVString();
		}
	}
  function generateJSONResponse($encapsStart="",$encapsEnd=""){
		header("Content-type: application/json");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $encapsStart;
		if (!is_null ($this->dataprocessor)){
			echo $this->dataprocessor->getJSONStringFromCSV();
		}
		echo $encapsEnd;
	}
}