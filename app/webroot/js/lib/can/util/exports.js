steal('can/util', function( can ) {

	// Register as an AMD module if supported
	if ( typeof define === "function" && define.amd ) {
		define(function() {
			return can;
		});
	}

});
