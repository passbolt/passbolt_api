module.exports = function( grunt ) {

	// Built-in tasks
	grunt.initConfig({
		qunit: {
			index: [
				"http://localhost/jupiter/ci/tmp/qunit.html"
//				"http://localhost:8000/can/test/can_jquery.html",
//				"http://localhost:8000/can/test/can_dojo.html",
//				"http://localhost:8000/can/test/can_yui.html",
//				"http://localhost:8000/can/test/can_zepto.html",
//				"http://localhost:8000/can/test/can_mootools.html"
			]
		},
		server: {
			port: 8000,
			base: ".."
		}
	});

	grunt.registerTask("test", [ "server", "qunit" ]);
	grunt.registerTask("default", "test");

	// Load external tasks
	// grunt.loadTasks("util/grunt");
};
