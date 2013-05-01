var path = require('path');

// A grunt task that writes the banner to every file
module.exports = function (grunt) {
	grunt.registerMultiTask('bannerize', 'Adds the banner to a set of files', function () {
		var options = grunt.config.process(['bannerize', this.target]);
		var banner = this.data.banner;

		grunt.file.expand(this.data.files).forEach(function (file) {
			var outFile = options.out ? path.join(options.out, path.basename(file)) : file;

			grunt.log.writeln('Adding banner to ' + file);
			grunt.file.write(outFile, banner + grunt.file.read(file));
		});
	});
}
