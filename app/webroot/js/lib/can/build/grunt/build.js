var spawn = require("child_process").spawn,
	path = require("path"),
	jsDir = path.join( __dirname, "../../..");


module.exports = function( grunt ) {

	grunt.registerTask("build", "Builds CanJS using Steal", function() {
		var done = this.async(),
			build = spawn("./js", ["can/util/make.js"], {
				cwd: jsDir
			});

		build.stdout.on("data", function( buf ) {
			grunt.log.write( "" + buf );
		});

		build.on("exit", function( code ) {
			done();
		});

		grunt.log.write("Building CanJS with Steal...\n");
	});

};
