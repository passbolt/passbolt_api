/*
 * grunt-beautify.git
 * https://github.com/pix/grunt-beautify
 *
 * Copyright (c) 2012 Camille Moncelier
 * Licensed under the MIT license.
 */
var beautifier = require('js-beautify');

module.exports = function (grunt) {

	// Please see the grunt documentation for more information regarding task and
	// helper creation: https://github.com/cowboy/grunt/blob/master/docs/toc.md
	// ==========================================================================
	// TASKS
	// ==========================================================================a
	var default_options = {
		indentSize : 2
	};

	grunt.registerMultiTask('beautify', 'Javascript beautifier', function () {
		var options = null;
		var tmp = grunt.config(['beautifier', this.target, 'options']);
		if (typeof tmp === 'object') {
			grunt.verbose.writeln('Using "' + this.target + '" beautifier options.');
			options = tmp;
		} else {
			tmp = grunt.config('beautifier.options');
			if (typeof tmp === 'object') {
				grunt.verbose.writeln('Using master beautifier options.');
				options = tmp;
			} else {
				grunt.verbose.writeln('Using beautifier default options.');
				options = default_options;
			}
		}

		// Beautify specified files.
		var excludes = grunt.config(['beautifier', this.target, 'exclude']);

		grunt.file.expand(this.filesSrc).filter(function (file) {
			if(/\.min\./.test(file)) {
				grunt.log.writeln('Not beautifying ' + file);
				return false;
			}

			for (var i = 0; i < excludes.length; i++) {
				if (excludes[i].test(file)) {
					grunt.log.writeln('Not beautifying ' + file);
					return false;
				}
			}
			return true;
		}).forEach(function (filepath) {
				grunt.log.writeln('Beautifying ' + filepath);
				var result = beautifier(grunt.file.read(filepath), options);
				grunt.file.write(filepath, result);
			});

	});

};