<?php
//(c) Andreas Galazis 2013
class Dataprocessor{
	private $uri="";
	private $csvStr="";
	private $lastValidatedCompany="";
	function  __construct($givenURI){
	   $this->uri=$givenURI;
	
	}
   //I use curl because it might be a pain 
   //to configure file_get_contents on shared hosting
	function loadFromURI(){
		$ch = curl_init($this->uri);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$ret = curl_exec($ch);
    	curl_close($ch);
    	return $ret;
	}
	//were label is the index of the label 
	//and categoryArr  is the index of category descriptors
   function getStructuredData($label, $categoryArr){
   	$data = str_getcsv($this->getCSVString(), "\n");
   	array_shift($data);
   	$structuredData=array();
   	for ($i = 0; $i < count($data); $i++) {
   		$row = str_getcsv($data[$i], ",");
   		$categories=Array();
   		for ($j = 0; $j < count($categoryArr); $j++) {
   			if($row[$categoryArr[$j]]!="n/a"){
   				$categories[$j]=$row[$categoryArr[$j]];
   			}
   		}
   		$category=implode (" / ",$categories);
   		$structuredData[$i]=Array("label"=>$row[$label],"category"=>$category);
      }
  		return $structuredData;
   }
   
   private function setLastValidatedCompany($compName){
   	$this->lastValidatedCompany=$compName;
   }
   function getLastValidatedCompany(){
   	return $this->lastValidatedCompany;
   }
   //function that verifies the validity of a symbol
   //eg if it's existent in the file and tracks the validated 
   //company
   function findSymbol($symbolIndex,$companyIndex,$symbol){
   	
   	$data = str_getcsv($this->getCSVString(), "\n");
   	array_shift($data);
   	for ($i = 0; $i < count($data); $i++) {
   		$row = str_getcsv($data[$i], ",");
   		if (isset($row[$symbolIndex])&&($row[$symbolIndex]==$symbol)){
   		   $this->setLastValidatedCompany($row[$companyIndex]);
   			return true;
   		}
   	}
     return false;
   }
   function getJSONStringFromCSV(){
   	$array = array_map("str_getcsv", explode("\n", $this->getCSVString()));
		return json_encode($array);
   }
   //if the csv string was not loaded yet
   //load it using the curl function and
   // return the result
   function getCSVString(){
		if (empty($this->csvStr)){
   		$this->csvStr=$this->loadFromURI();
   	}
      return $this->csvStr;
   }
}
?>