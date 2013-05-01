var path = require("path");
var jsDir = path.join( __dirname, "../..");

module.exports = function( grunt ) {
	grunt.registerMultiTask('build', 'Runs build files.', function() {
		var done = this.async();
		var target = this.target;

		var options = grunt.config.process(['build', target]);
		var args = [this.data.src, this.data.out || 'dist/', this.data.version || 'edge'];
		var libraries = Array.isArray(this.data.libraries) ? this.data.libraries : [];

		args.push.apply(args, libraries);

		grunt.verbose.writeflags(this.data, 'Options');
		grunt.log.writeln('Running  ./js ' + args.join(' '));

		grunt.util.spawn({
			cmd : "./js",
			args : args,
			opts : {
				cwd: jsDir
			}
		}, function(error, result, code) {
			grunt.log.writeln('Done building');
			done();
		});

		grunt.log.write("Building " + this.data.src + " with Steal...\n");
	});
};
