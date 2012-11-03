var stl = steal('./setup.js')
	.then('./mvc_test.js')
	.then('can/construct/construct_test.js')
	.then('can/observe/observe_test.js')
	.then('can/observe/compute/compute_test.js')
	.then('can/view/view_test.js')
	.then('can/control/control_test.js')
	.then('can/model/model_test.js')
	.then('can/view/ejs/ejs_test.js')
	.then('can/util/util_test.js')
	.then(function(){
		// Does the browser support window.onhashchange? Note that IE8 running in
	    // IE7 compatibility mode reports true for 'onhashchange' in window, even
	    // though the event isn't supported, so also test document.documentMode.
	    var doc_mode = document.documentMode,
	    	supports_onhashchange = 'onhashchange' in window && ( doc_mode === undefined || doc_mode > 7 );

		if(window.jQuery && !supports_onhashchange){
			stl = stl('./hashchange');
		}
	});
	stl.then('can/route/route_test.js');