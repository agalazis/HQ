//(c) Andreas Galazis 2013

function drawGraph(serialisedForm){
var form=$("form[name=historical_quotes]")
	//json request using the urencoded data of the form
	$('input[type="submit"]').prop("disabled",true);
	$('#loader').show();
  	form.unbind("submit");
	form.bind("submit", function(event){
		event.preventDefault();
   		message("Please wait a moment... your request is being processed",[]);
	});
	$.ajax({
	   type: 'POST',
		url:'data.php',
		data: serialisedForm,
		dataType:'json',
		//if everything goes out as planned
		success: function(jsonReply) {
		//ready up the nvd3 datastructure
		$("#messagereceived").html(jsonReply.message);
		if (jsonReply.status==="success"){
			openObj={};
			openObj["key"]="Open";
			openObj["values"]=[];
			closeObj={};
			closeObj["key"]="Close";
			closeObj["values"]=[];
			//Yahoo API data provided in yyyy mm dd format
			var parseDate = d3.time.format("%Y-%m-%d").parse,
			//working on a copy
			 data=jsonReply.data.slice();
			 //ommit header
			data.shift();
		
			
			data.forEach(function(d) {
	 		//if not null
				if ((d!=null)&&(d[0]!=null)&&(d[1]!=null)&&(d[4]!=null)) {
					//openObj["values"].push([Math.round((parseDate(d[0]).getTime())),d[1]]);
					//closeObj["values"].push([Math.round((parseDate(d[0]).getTime())),d[4]]);
					openObj["values"].unshift([moment(d[0]).valueOf(),Number(d[1])]);
					closeObj["values"].unshift([moment(d[0]).valueOf(),Number(d[4])]);
					
				}
			});
			//initialise minimum maximum variables
			//they are needed to enforce the yaxis
			//range to avoid possible problems
			/*var bothVals=openObj["values"].concat(closeObj["values"])
			var maxValue= Math.max.apply(Math, bothVals);
			var minValue= Math.min.apply(Math, bothVals);*/
			data=[openObj,closeObj];
			console.log(JSON.stringify(data));
 			nv.addGraph(function() {
 				var chart = nv.models.lineChart()
    					.useInteractiveGuideline(true)
                  .x(function(d) { return  d[0]  })
                  .y(function(d) { return d[1] })
                  .color(d3.scale.category10().range());
				//creating date from unixformat
     			chart.xAxis
     				   .axisLabel("Date")
        				.tickFormat(function(d) {
            			return d3.time.format('%x')(new Date(d))
          			});
				//y axis is float dollars
    			chart.yAxis
    				.axisLabel("Opening/Closing price")
        			.tickFormat(function(d) { return '$' + d3.format(',.2f')(d) });
        		/*chart.forceY([minValue, maxValue]);
				console.log("max:"+maxValue+","+"min:"+minValue);*/				
				//load the data
				d3.select('#chart svg')
						.datum(data)
						.transition().duration(500)
						.call(chart);
				nv.utils.windowResize(chart.update);
    			return chart;
			});
			drawTable(jsonReply.data);
			
		}
	},
	complete: function() {
		$('#loader').hide();
		form.unbind("submit");
		form.bind("submit", function( event ) {
			event.preventDefault();
			submit(form);
		});
		$("messagereceived").empty();
	}
});
}
function drawTable(data){
	var table=$("#table");
	table.html($("#prototypetable").html());
	var tableentries=$("#table").children("#tableentries");
	//table.empty();
	
	var headers="<th>"+data.shift().join("</th><th>")+"</th>";
	tableentries.find('tr#header').html(headers);
	tableentries.find('tr#footer').html(headers);
	data.forEach(function(d) {
		table+="<tr><td>";
		table+=d.join("</td><td>");
		table+="</td></tr>";
	});
	update=(tableentries.children("tbody")!=="");
	tableentries.children("tbody").html(table);
	applybootstrap();
   $("#table").show();
}
function applybootstrap(){
	$.extend($.tablesorter.themes.bootstrap, {
	// these classes are added to the table
	table      : 'table table-bordered',
	caption    : 'caption',
	header     : 'bootstrap-header', //gradient background
	footerRow  : '',
	footerCells: '',
	icons      : '', //
	sortNone   : 'bootstrap-icon-unsorted',
	sortAsc    : 'icon-chevron-up glyphicon glyphicon-chevron-up',     // Bootstrap classes Asc
	sortDesc   : 'icon-chevron-down glyphicon glyphicon-chevron-down', // Bootstrap classes Desc
	active     : '',
	hover      : '',
	filterRow  : '', 
	even       : '', 
	odd        : ''  
  });
  // call the tablesorter plugin - apply the uitheme widget
  $("#tableentries").tablesorter({
    theme : "bootstrap",
    widthFixed: true,
    headerTemplate : '{content} {icon}', // bootstrap icon!
    widgets : [ "uitheme", "filter", "zebra" ],
    widgetOptions : {
      zebra : ["even", "odd"],
      filter_reset : ".reset"}
  })
  .tablesorterPager({
    container: $(".ts-pager"),
    cssGoto  : ".pagenum",
    removeRows: false,
    output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

  });
  $(function(){

		// filter button
		$('button.filter').click(function(){
			var col = $(this).data('column'),
				txt = $(this).data('filter');
			$('#tableentries').find('.tablesorter-filter').val('').eq(col).val(txt);
			$('#tableentries').trigger('search', false);
			return false;
		});

		// toggle zebra widget
		$('button.zebra').click(function(){
			var t = $(this).hasClass('btn-success');
			$('#tableentries')
				.toggleClass('table-striped')[0]
				.config.widgets = (t) ? ["uitheme", "filter"] : ["uitheme", "filter", "zebra"];
			$(this)
				.toggleClass('btn-danger btn-success')
				.find('i')
				.toggleClass('icon-ok icon-remove glyphicon-ok glyphicon-remove').end()
				.find('span')
				.text(t ? 'disabled' : 'enabled');
			$('#tableentries').trigger('refreshWidgets', [false]);
			return false;
		});
	});
}
