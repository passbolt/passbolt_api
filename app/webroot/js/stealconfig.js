steal.config({
	map: {
		"*": {
			"jquery/jquery.js": "jquery",
			"can": "lib/can",
			"lib/can/util/util.js": "lib/can/util/jquery/jquery.js",
			"jquery/": "lib/jquerypp/",
			"jquery": "lib/jquerypp/empty.js",
			"funcunit": "lib/funcunit",
			"mad": "lib/mad"
		}
	},
	paths: {
		// it will be used by our view class to fix a bug of the can view class which
		// does not used the stealconfig file right now
		"mad/": "lib/mad/"
	},
	shim : {
		jquery: {
			exports: "jQuery"
		}
	},
	ext: {
		js: "js",
		json: "json",
		css: "css",
		ejs: "can/view/ejs/ejs.js"
	}
});
