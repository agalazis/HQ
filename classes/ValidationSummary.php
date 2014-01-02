<?php
//(c) Andreas Galazis 2013
abstract class ValidationSummary{
	private $summaryArr=NULL;
	private $successHeading="";
	private $errorHeading="";
	abstract protected function summarise();
	abstract protected function wrapMessage($isSuccess,$message);
	function  __construct($success="Success!",
											$error="You have the following errors:" ){
		$this->summaryArr=array();
		$this->successHeading= $success;
		$this->errorHeading= $error;
	}
	function addErrMsg($string){
		$this->messageArray[]=$string;
		//to allow inline code:
		return true;
	}
	function getSuccessHeading(){
		return $this->successHeading;
	}
	function setSuccessHeading($heading){
		return $this->successHeading=$heading;
	}
	function getErrorHeading(){
		return $this->errorHeading;
	}
	function setErrorHeading($heading){
		return $this->errorHeading=$heading;
	}
	function getSummaryArr(){
		return $this->summaryArr;
	}
	
}
//by using an abstract it's easy create custom implementations
//of printing methods eg wrap messages with different tags
//and manipulate messages differently
class ValidationSummaryList extends ValidationSummary{
		const CLOSE="<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
	function wrapMessage($isSuccess,$message){
		 $cssclass=($isSuccess)?"alert-success":"alert-warning";
	    return "<div class='alert $cssclass alert-dismissable'>".$this::CLOSE.$message."</div>";
	} 	
	function summarise(){
		//construct messages accordingly
		if ((count($this->getSummaryArr())==0)){
			$message="<h4><strong>Success!</strong>".$this->getSuccessHeading()."</h4>";
		}
		else{
			$message="<h4><strong>Error!</strong>".$this->errorHeading()."</h4>";
			$message.="<ul><li>" . implode("</li><li>", $this->getSummaryArr()) . "</li></ul>";
		}
	  
		return  $this->wrapMessage((count($this->getSummaryArr())==0),$message);
	}

}