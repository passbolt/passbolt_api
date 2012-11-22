steal.config({
	map: {
		"*": {
			"can/util/util.js": "can/util/zepto/zepto.js"
		}
	}
});
steal('can/util/mvc.js');