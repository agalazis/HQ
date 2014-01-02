//(c) Andreas Galazis 2013
function validateEmail(email) {
	var emailReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/ ;
  	return emailReg.test( email ) ;
}
function validateSymbol(symbol){
	return (window.symbols.filter(function(v){ return v["label"] == symbol ; }).length>0);
}
function symbolValidation(symbol){
	v=validateSymbol(symbol);
  	message((v)?"Valid symbol":"Invalid Symbol",[],v);
}
function dateIsInvalid(date){
	var regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/ ;
  	return (isNaN(Date.parse(date))||!regex.test(date));
}
function dateValidation(date){
  v=dateIsInvalid(date);
  message((v)?"Invalid date Input!":"Date Valid!",[],!v);
}
function emailValidation(email){
	v=validateEmail(email) ;
	message((v)?"Your email is valid!":"Your email is invalid!",[],v);
}
function validateDateRange(startDateStr,endDateStr){
   return ((moment(startDateStr).diff(endDateStr))<=0);
}
function formIsValid(){
	messages=Array();
   if (!validateEmail($("input[name=email]").val())){
   	messages.push("Invalid email.");
   }
	if(!validateSymbol($("input[name=symbol]").val())){
		messages.push("Invalid symbol.");
	}
   from=$("input[name=from]").val();
   to=$("input[name=to]").val();
	vf=dateIsInvalid(from);
	vt=dateIsInvalid(to);
	if(vf){
		messages.push("Date from is invalid.");
	}
	if(vt){
		messages.push("Date to is invalid.");
	}
	else if(!vf&&!vt&&!validateDateRange(from,to)){
		messages.push("Invalid Date Range.");
	}
	if(messages.length>0){
		message("The following problems were found",messages,false);
		return false;
	}
	return true;
}
//the only optional is success for various reasons
function message(messageTitle,bodyArr,isSucess){
	if (typeof isSucess == 'undefined'){
		specClass="alert-warning";
		strong="Message: ";
	}
	else if (isSucess){
		specClass="alert-success";
		strong="Success: ";
	}
	else{
		specClass="alert-danger";
		strong="Error: ";
	}
	if (bodyArr.length>0) {
		boddyText="<ul><li>"+bodyArr.join("</li><li>")+ "</li></ul>";
	}
   else{
   	boddyText="";
   }
	closebutton='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
	
   $("#messagereceived").html($('<div></div>').addClass('alert ' + specClass + ' alert-dismissable')
   								.append(closebutton+"<h4><strong>"+strong+"<strong>"+messageTitle+"<h4>"+boddyText));
}
function submit(form,tableentries){
	$("messagereceived").empty();
	if (formIsValid()){
		var serialisedForm=$( form ).serialize();
		$("svg").empty();
		$("#table").hide();
		drawGraph(serialisedForm);
	}
}
$(document).ready(function(){
	$("form[name=historical_quotes]").on("submit", function( event ) {
		event.preventDefault();
		submit(this);
	});
});

