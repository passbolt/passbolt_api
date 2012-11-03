steal("steal/preload").then(function() {

	var time = (+new Date);

	/**/
	steal.preload("./mock/dude?sleep=2&bust=" + time).done(function() {
		console.log("one");
	});
	/**/
	steal("./mock/dude?sleep=2&bust=" + time).then(function() {
		console.log("two");
	});

});
