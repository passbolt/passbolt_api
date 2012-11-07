// steals the 3 things neded
if ( window.location.search.indexOf("DIST=1") > -1 ) {
	steal("can/util/jquery/jquery.1.8.2.js").then("can/dist/edge/can.jquery.js");
} else {
	steal(
		'can/model',
		'can/control/route',
		'can/view/ejs'
	).then(
		'can/util/exports.js'
	);
}
