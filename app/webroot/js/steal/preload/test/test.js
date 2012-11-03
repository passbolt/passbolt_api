steal(
	"funcunit/qunit",
	"steal/preload"
).then(function() {
	
	module("Preload")
	var time = (+new Date);
	asyncTest( "Requesting two.js which takes two seconds to load.", function() {

		// Preload these which takes two seconds
		steal.preload(
			"./mock/one?sleep=1&bust=" + time,
			"./mock/two?sleep=2&bust=" + time
		).done(function() {

			ok( ! window.two, "two.js doesn't exist yet.");
			
			// Execute two which should happen near instantly
			steal(
				"./mock/one?sleep=1&bust=" + time,
				"./mock/two?sleep=2&bust=" + time
			);
		});
		
		// Check if window.two exists after 2.5 seconds
		setTimeout(function(){
				ok( window.two, "Steal loaded two.js instantly from cache.");
				ok( window.one, "Steal loaded two.js instantly from cache.");
				start();
		}, 3000);

	});

});
