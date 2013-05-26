var theData;    //  A container for the last week's worth of data

var placeholders = {
	'latestIntTemp'		: $('#latest-int-temp'),
	'latestExtTemp' 	: $('#latest-ext-temp'),
	'latestTimeStamp' 	: $('#latest-timestamp'),
	'lowestIntTemp'		: $('#lowest-int-temp'),
	'highestIntTemp' 	: $('#highest-int-temp'),
	'tempDiff'			: $('#difference')
}

function getWeeksData()
{
	var today = new Date();
	var lastWeek = new Date();
	lastWeek.setDate(today.getDate()-7);

	$.ajax({
		url: 'get_json.php?sd=' + formatDate(lastWeek),
	 	success: function(d,e,f){
	 		theData = d;
	 		getRequiredData();
	 	},
	 	error: function(a,b,c){
	 		console.log(a,b,c);
	 	}
	});
}

function roundToTwo(nm,adspn)
{
	var rdd = Math.round(nm * 100) / 100 + '<span class="degree">°</span>';
	if(adspn)
	{
		var gr = rdd.split(".");
		rdd =  gr[0] + '<small>.' + gr[1] + '</small>'; 
	}
	return rdd;
}

function formatDate(dt) //	to YYYYMMDD format for querystring
{
	//	user113716 @ http://stackoverflow.com/questions/3605214/javascript-add-leading-zeroes-to-date
	//	Lovely solution...

	return dt.getFullYear() + ('0' + (dt.getMonth()+1)).slice(-2) + ('0' + dt.getDate()).slice(-2);
}

function giveSafariaHand(dt)	//	Safari doesn't do dates like other browsers...
{
	//	Break this down:  2013-01-29 00:29:49
	dt = dt.split(" ");
	dt[0] = dt[0].split("-");	//	Date into [y,m,d]
	dt[1] = dt[1].split(":");	//	Time into [h,m,s]
	return dt;
}

function formatPrettyDate(dt)	// to 23:45, 1 Jan 2013
{
	dt = giveSafariaHand(dt);
	dt = new Date(dt[0][0],dt[0][1],dt[0][2],dt[1][0],dt[1][1]);
	var monthsArr = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
	return dt.getHours() + ":"	+ ("0" + dt.getMinutes()).slice(-2) + ", " + dt.getDate() + " " + monthsArr[dt.getMonth()-1] + " " + dt.getFullYear();
}

function getRequiredData()
{		
	var arr = Object.keys(theData).map(function ( key ) { return theData[key]['g_intread']; });

	var min = Math.min.apply( null, arr );
	var max = Math.max.apply( null, arr );
	var latestIntTempVal = roundToTwo(theData[theData.length-1]['g_intread'],true);
	var latestExtTempVal = theData[theData.length-1]['g_extread'];
	var goingUp = (theData[theData.length-1]['g_intread'] > theData[theData.length-2]['g_intread']) ? "up" : "down";

	placeholders.latestIntTemp.html(latestIntTempVal);
	placeholders.latestExtTemp.html(latestExtTempVal);
	placeholders.latestTimeStamp.html(formatPrettyDate(theData[theData.length-1]['g_datetime']));
	placeholders.lowestIntTemp.text(Math.round(min*100)/100);
	placeholders.highestIntTemp.text(Math.round(max*100)/100);
	placeholders.tempDiff.removeClass("up","down").addClass(goingUp);

	doLineChart(theData);
}


$(function() {
	getWeeksData();
	var loader = setInterval(getWeeksData,120000);	//	2 minutes
});