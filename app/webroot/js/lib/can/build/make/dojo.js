steal.config({
	map: {
		"*": {
			"can/util/util.js": "can/util/dojo/dojo.js"
		}
	}
});
steal('can/util/mvc.js');