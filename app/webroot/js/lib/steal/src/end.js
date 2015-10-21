	if( isNode ) {
		require('systemjs');
			
		global.steal = makeSteal(System);
		global.steal.System = System;
		global.steal.dev = require("./ext/dev.js");
		steal.clone = cloneSteal;
		module.exports = global.steal;
		global.steal.addSteal = addSteal;
		require("system-json");
		
	} else {
		var oldSteal = global.steal;
		global.steal = makeSteal(System);
		global.steal.startup(oldSteal && typeof oldSteal == 'object' && oldSteal)
			.then(null, function(error){
				console.log("error",error,  error.stack);
				throw error;
			});
		global.steal.clone = cloneSteal;
		global.steal.addSteal = addSteal;
	} 
    
})(typeof window == "undefined" ? (typeof global === "undefined" ? this : global) : window);
