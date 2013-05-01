var ejs = require('ejs');
var beautify = require('js-beautify');

module.exports = function(grunt) {
	var _ = grunt.util._;

	grunt.registerMultiTask('testify', 'Generates test runners', function() {
		var done = this.async();
		var data = this.data;
		var template = grunt.file.read(this.data.template);
		var transform = this.data.transform || {};
		var modules = this.data.builder.modules;
		var configurations = this.data.builder.configurations;

		_.each(configurations, function(config, configurationName) {
			var options = {
				configuration: config,
				modules: [],
				tests: [],
				root: data.root,
				'_': _
			};

			_.each(modules, function(definition, key) {
				if(!definition.configurations || definition.configurations.indexOf(configurationName) !== -1) {
					var name = key.substr(key.lastIndexOf('/') + 1);
					var mod = transform.module ? transform.module(definition, key) : key;
					var test = transform.test ? transform.test(definition, key) : (key + '/' + name + '_test.js');

					mod && options.modules.push(mod);
					test && options.tests.push(test);
				}
			});

			_.extend(config.steal, {
				root: data.root
			});

			if(transform && transform.options) {
				_.extend(options, transform.options.call(config, configurationName));
			}

			var lib = '<!-- AUTO GENERATED - DO NOT MODIFY -->\n'+
				beautify.html(ejs.render(template, options), {
					"wrap_line_length": 70
				});

			grunt.log.writeln('Generating ' + data.out + configurationName + '.html');
			grunt.file.write(data.out + configurationName + '.html', lib);
		});

		done();
	});
};