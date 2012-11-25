steal('can/util/mvc.js')
.then('funcunit/qunit', 
	  'can/util/fixture')
.then(function() {
	var oldmodule = window.module;

	// Shim console, because IE 9 and under (and maybe something else?) doesn't have it,
	// and maybe some other browsers have under-defined versions.
	// This shim is shamelessly and ruthlessly stolen from 
	// HTML5 Boilerplate: https://github.com/h5bp/html5-boilerplate/blob/master/js/plugins.js
	(function() {
	    var method;
	    var noop = function noop() {};
	    var methods = [
	        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
	        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
	        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
	        'timeStamp', 'trace', 'warn'
	    ];
	    var length = methods.length;
	    var console = (window.console = window.console || {});

	    while (length--) {
	        method = methods[length];

	        // Only stub undefined methods.
	        if (!console[method]) {
	            console[method] = noop;
	        }
	    }
	}());

	// Set the test timeout to five minutes
	QUnit.config.hidepassed = true;
	QUnit.config.testTimeout = 300000;
	if ( typeof console !== "undefined" && console.log ) {
		QUnit.log(function( details ) {
			if ( ! details.result ) {
				console.log( "FAILURE: ", details.result, details.message );
			}
		});
	}

	if(window.TESTINGLIBRARY !== undefined) {
		window.module = function(name, testEnvironment) {
			oldmodule(TESTINGLIBRARY + '/' + name, testEnvironment);
		}
	}
})
