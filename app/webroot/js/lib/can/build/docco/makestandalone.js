load("steal/rhino/rhino.js");
steal('steal/build/pluginify', 'steal/clean', function() {

	var libs = {
		"jquery" : "jquery.1.8.2.js",
		"mootools" : "mootools-core-1.4.3.js",
		"zepto" : "zepto.0.8.js",
		"dojo" : "dojo.js",
		"yui" : "yui.js"
	}, lib, exclude;
	
	steal.File("can/util/docco/standalone").mkdirs();

	for ( lib in libs ) {

		exclude = libs[ lib ];

		steal.build.pluginify("can/util/make/" + lib + ".js", {
			out : "can/util/docco/standalone/can." + lib + ".js",
			global : "can = {}",
			onefunc : true,
			compress: false,
			skipCallbacks: true,
			exclude : "can/util/" + lib + "/" + exclude
		});

		steal.clean("can/util/docco/standalone/can." + lib + ".js");

	}

});
