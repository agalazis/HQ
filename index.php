<html lang="en">
<head>
 <!--(c) Andreas Galazis 2013-->
  <meta charset="utf-8">
  <title>Historical Quotes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="root" >

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/icon.ico">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/validation.js"></script>
	<script src="js/jquery.tablesorter.js"></script>
	<link rel="stylesheet" href="css/tablesorter.bootstrap.css">
	<script src="js/jquery.tablesorter.widgets.js"></script>
	<link rel="stylesheet" href="css/jquery.tablesorter.pager.css">
	<script src="js/jquery.tablesorter.pager.js"></script>
	 <style>
.ui-autocomplete-category {
font-weight: bold;
padding: .2em .4em;
margin: .8em 0 .2em;
line-height: 1.5;
}
</style>
<script>
$.widget( "custom.catcomplete", $.ui.autocomplete, {
_renderMenu: function( ul, items ) {
var that = this,
currentCategory = "";
$.each( items, function( index, item ) {
if ( item.category != currentCategory ) {
ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
currentCategory = item.category;
}
that._renderItemData( ul, item );
});
}
});
function addHandlers() { 
	$(function() {
		$( "#from" ).datepicker({
			defaultDate: "0",
			maxDate: "0",
			changeMonth: true,
			changeYear:true,
			yearRange: "-100:+0",
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
		$( "#to" ).datepicker( "option", "minDate", selectedDate );
	}
	});
	$( "#to" ).datepicker({
		defaultDate: "0",
		changeMonth: true,
		changeYear:true,
		yearRange: "-100:+0",
		numberOfMonths: 1,
		maxDate: "0",
		onClose: function( selectedDate ) {
		$( "#from" ).datepicker( "option", "maxDate", selectedDate );
	}
	});
	});
	} 
</script>
<script>
window.symbols = <?php
flush();
include_once("./classes/Dataprocessor.php");
$data= new Dataprocessor("www.nasdaq.com/screening/companies-by-name.aspx?&render=download");
echo json_encode($data->getStructuredData(0,array(6,7)));
?>;
$(function() {
$( "#search" ).catcomplete({
delay: 0,
source: window.symbols
});
});
</script>
</head>

<body onload="addHandlers();$('#loader').hide();$('#table').hide();">
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<h3>
				 Historical Quotes
			</h3>
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="#">HQ</a>
				</div>
				 
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="#">Home</a>
						</li>
						<li>
							<a href="http://finance.yahoo.com/">Y! finance</a>
						</li>						
						
					</ul>

				</div>
				
			</nav><div id="messagereceived">
			</div>
			<div class="row clearfix">
				<div class="col-md-12 column">
					<form name="historical_quotes" class="form-inline" role="form">
						<div class="form-group">
							 <label for="email">Email address</label><input class="form-control" id="email" type="text" name="email" onchange="emailValidation(this.value);">
						</div>
						<div class="form-group">
						<label for="symbol">Symbol: </label>
						<input id="search" name="symbol" class="form-control" onchange="symbolValidation(this.value);">
						</div>
						
						<div class="form-group">
							 	<label for="from">From</label><br>
								<input type="text" id="from" name="from" class="form-control" style="{width:8em} " onchange="dateValidation(this.value);">
						</div>
						<div class="form-group">
								<label for="to">to</label><br>
								<input type="text" id="to" name="to" class="form-control" style="{width:8em}" onchange="dateValidation(this.value);">
						</div> <button id="graphbutton" type="submit" class="btn btn-default" style="margin-top:1.8em;">Submit <img id="loader" src="img/loader.gif" style="display:inline; width:1em; height:1em;"></button>
						
						
					</form>
				</div>
			</div>
			<div class="jumbotron">
				<p>
					Please fill the the above form and submit it in order to retrieve the data
				</p>
				<?php include("nvd3chart.php")?>
			</div>
			<?php include_once("table.php");?>
		</div>
	</div>
</div>
<div class="navbar navbar-fixed-bottom" onclick="location.href='http://andreas.galazis.com/'" >&copy; Andreas Galazis 2013</div>
</body>
</html>
