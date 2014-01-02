<?php
//(c) Andreas Galazis 2013
	ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
	require_once './classes/Dataprocessor.php';
	require_once './classes/CSVproxy.php';
	require_once './classes/FormHelper.php';
	require_once './classes/ValidationSummary.php';
	require './mailer/PHPMailerAutoload.php';
	
	if(isset($_POST['email'])&&isset($_POST['symbol'])
									&&isset($_POST['from'])
									&&isset($_POST['to'])){
		
		$email= $_POST['email'];
		$from=$_POST['from'];
		$to=$_POST['to'];
		$symbol=$_POST['symbol'];
		$nasdaqDP= new Dataprocessor("www.nasdaq.com/screening/companies-by-name.aspx?&render=download");								
		$validationSummary= new ValidationSummaryList("Success an email has been sent at ".$email);
		$csvProxy= new CSVproxy(FormHelper::getURIFromData($symbol,$from,$to));
		if (FormHelper::validateForm($nasdaqDP,$validationSummary,$email,$symbol,$from,$to)){
			//set timezone
			date_default_timezone_set('Europe/Athens');
			//Send an email using the Company name as subject and the Start Date and End Date as body
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//MTP debugging 
			$mail->SMTPDebug = 0;
			//debug output
			$mail->Debugoutput = 'html';
			//host
			$mail->Host = "smtp.gmail.com";
			//Secure conection
			$mail->SMTPSecure  = "tls"; 
			//Set the SMTP port number 
			$mail->Port = 587;
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//content settings
			$mail->CharSet     = 'UTF-8';
  			$mail->Encoding    = '8bit';
  			//subject
			$mail->Subject = $nasdaqDP->getLastValidatedCompany();
			//body
			$mail->Body = "from ".$from." to ".$to;
			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = "yourmail";
			//Password to use for SMTP authentication
			$mail->Password = "yourpass";
			//Sender
			$mail->setFrom('yourmail', 'yourname');
			//receiver
			$mail->addAddress($email);

				
			//send the message
			if (!$mail->send()) {
				//gives success status for data not email
				$validationSummary->setSuccessHeading("Valid form submission-but (failed to send message)"); 
			} 
				$encapsulationStart="{\"status\":\"success\",\"message\":\"".$validationSummary->summarise()."\",\"data\":";
				$encapsulationEnd="}";
	   		$csvProxy->generateJSONResponse($encapsulationStart,$encapsulationEnd);
			
		}
		else{
			$csvProxy= new CSVproxy();
			$encapsulationStart="{\"status\":\"error\",\"message\":\"".$validationSummary->summarise()."\"}";
			$csvProxy->generateJSONResponse($encapsulationStart);
		}
 		 $mail->SmtpClose();
	
	}else{
		$csvProxy= new CSVproxy();
		$encapsulationStart="{\"status\":\"error\",\"message\":\"missing fields\"}";
	   $csvProxy->generateJSONResponse($encapsulationStart);
	}		
?>