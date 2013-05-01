steal.config({
	map: {
		"*": {
			"jquerypp/": "lib/jquerypp/",
			"jquery/jquery.js" : "jquery",
			"can/": "lib/can/",
			"can/util/util.js": "can/util/jquery/jquery.js",
			"jquery/": "lib/jquerypp/",
			"mad/": "lib/mad/"
		}
	},
	paths: {
		"mad/": "lib/mad/",
		"jquery/": "jquerypp/",
		"jquery": "lib/can/lib/jquery.1.8.3.js",
		"mootools/mootools.js" : "can/lib/mootools-core-1.4.5.js",
		"dojo/dojo.js" : "can/util/dojo/dojo-1.8.1.js",
		"yui/yui.js" : "can/lib/yui-3.7.3.js",
		"zepto/zepto.js" : "can/lib/zepto.1.0rc1.js"
	},
	shim : {
		jquery: {
			exports: "jQuery"
		}
	},
	ext: {
		js: "js",
		css: "css",
		less: "steal/less/less.js",
		coffee: "steal/coffee/coffee.js",
		ejs: "can/view/ejs/ejs.js",
		mustache: "can/view/mustache/mustache.js"
	}
})

	// map: {
		// "*": {
			// "jquery/jquery.js": "jquery",
			// "can": "lib/can",
			// "lib/can/util/util.js": "lib/can/util/jquery/jquery.js",
			// "jquery/": "lib/jquerypp/",
			// "jquery": "lib/jquerypp/empty.js",
			// "funcunit": "lib/funcunit",
			// "mad": "lib/mad"
		// }
	// },

