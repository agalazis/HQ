<?php
/*(c) Andreas Galazis 2013
class that provides static helper methods
for validating the form and processing 
its data eg. generating the uri*/
require_once 'Dataprocessor.php';
require_once 'ValidationSummary.php';
date_default_timezone_set('Europe/Athens');
class FormHelper{
	const BASEURI="http://ichart.yahoo.com/table.csv";
	const SYMBOLINDEX=0;
	const COMPANYINDEX=1;
	static function emailIsValid ($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL); 
	}
   static function symbolIsValid($inputSymbol,&$dataprocessor){
		return $dataprocessor->findSymbol( self::SYMBOLINDEX, self::COMPANYINDEX,$inputSymbol);
							
		
	}
	static function dateIsValid($date,$format='m/d/Y'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
   //precondition: the dates are valid
	static function checkedDateRangeIsValid($dateFromStr,$dateToStr){
		return (new DateTime(date($dateFromStr))>new DateTime(Date($dateToStr)));
	}
   //precondition for calling the function with no attributes
   //is that the existence of request parameters is checked beforehand
	static function validateForm(&$dataprocessor,&$validationSummary,$email,$symbol,$from,$to) {
		if((self::emailIsValid($email)||$validationSummary->addErrMsg('Invalid email'))
		  &&(self::symbolIsValid($symbol,$dataprocessor)||$validationSummary->addErrMsg('Invalid Symbol'))
		  &&(self::dateIsValid($from)||$validationSummary->addErrMsg('Invalid "From" date value'))
		  &&(self::dateIsValid($to)||$validationSummary->addErrMsg('Invalid "To" date value'))
		  &&(self::checkedDateRangeIsValid($from,$to)||$validationSummary->addErrMsg('Invalid date range'))){
		  	return (count($validationSummary->getSummaryArr())==0);
		  	}  
	}
   private static function getArgsFromDate(&$uriArgsArray,&$currentArg,$date){
   	   $first=true;
   	foreach( explode("/",$date) as $uriArg ){
   		if($first){
				$uriArg--;
				$first=false;   		
   		}
			$uriArgsArray[]=$currentArg."=".$uriArg;
			$currentArg++;
		}
   
   }
   
   //precondition validate symbol+dates
	static function getURIFromData($symbol,$from, $to){
		$uriArgs=array();
		$uriArgs[0]="s=$symbol";
		$curArg="a";
		self::getArgsFromDate($uriArgs,$curArg,$from);
		self::getArgsFromDate($uriArgs,$curArg,$to);
		return self::BASEURI."?" . implode("&", $uriArgs)."&g=w&ignore=.csv";
   }
}
?>