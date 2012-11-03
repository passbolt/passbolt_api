steal.config({
	map: {
		"*": {
			"jquery/jquery.js": "jquery",
			"can": "lib/can",
			"lib/can/util/util.js": "lib/can/util/jquery/jquery.js",
			"jquery/": "lib/jquerypp/",
			"jquery": "lib/jquerypp/empty.js",
			"funcunit": "lib/funcunit"
		}
	},
	paths: {
//		"jquery": "lib/jquery-1.8.2.min.js"
	},
	ext: {
		js: "js",
		json: "json",
		css: "css",
		ejs: "can/view/ejs/ejs.js"
	}
});