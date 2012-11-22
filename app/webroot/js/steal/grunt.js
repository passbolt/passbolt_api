var fs = require('fs');
var Promise = require('node-promise/promise');
var readFile_promise = Promise.convertNodeAsyncFunction(fs.readFile);

module.exports = function (grunt) {
	var codestyle = {
		options : {
			indentSize : 1,
			indentChar : "\t"
		}
	};

	grunt.registerTask("build", function() {
		var done = this.async();
		fs.readFile(grunt.config('build').file, 'utf8', function (err, core) {
			var promises = [];
			if (err) {
				return console.log('An error occured while reading core/core.js file')
			}

			var matches = core.match(/\/\*#\s+(.*?)\s+#\*\//g);
			matches.forEach(function (file) {
				promises.push(readFile_promise("core/" + file.slice(3, -3).trim()));
			});

			Promise.all(promises).then(function (results) {
				var out = grunt.config('build').out;
				for (var i = 0; i < results.length; i++) {
					core = core.replace(matches[i], results[i]);
					console.log("-- Adding core/" + matches[i].slice(3, -3).trim())
				}
				fs.writeFile(out, core, function (err) {
					if (err) {
						return console.log('There was an error with writing ' + out)
					}
					console.log(out + ' was successfully built.')
					done();
				})
			})
		});
	});

	grunt.initConfig({
		pkg : '<json:package.json>',
		meta : {
			banner : '/*! <%= pkg.title || pkg.name %> - <%= pkg.version %> - ' +
				'<%= grunt.template.today("yyyy-mm-dd") %>\n' +
				'<%= pkg.homepage ? "* " + pkg.homepage + "\n" : "" %>' +
				'* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
				' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */'
		},
		out : 'steal.js',
		build : {
			file : 'core/core.js',
			out : '<config:out>'
		},
		watch: {
			scripts: {
				files: "core/*.js",
				tasks: "default"
			}
		},
		beautifier : {
			steal : codestyle,
			core : codestyle
		},
		min : {
			dist: {
				src: [ '<banner:meta.banner>', '<config:out>'],
				dest: 'steal.production.js'
			}
		},
		beautify : {
			steal : '<config:out>',
			core : 'core/**/*.js'
		}
	});

	grunt.loadNpmTasks('grunt-beautify');
	grunt.registerTask('default', 'build beautify:steal min:dist');
};
