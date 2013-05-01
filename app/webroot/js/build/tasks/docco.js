/**
 * Grunt Docco task. Based on https://github.com/DavidSouther/grunt-docco
 */
var docco = require('docco');

module.exports = function(grunt) {
	grunt.registerMultiTask('docco', 'Docco processor.', function() {
		var _ = grunt.util._;
		var done = this.async();
		var options = this.options();
		var src = grunt.file.expand(this.data.files).filter(function(file) {
			return !_.some(options.exclude, function(exclude) {
				return exclude.test(file);
			});
		});

		docco.document( _.extend({ args: src }, this.data.docco, options.docco), function(err, result, code){
			grunt.log.writeln("Doccoed [" + src.join(", ") + "]; " +
				err ? err : "(No errors)" + "\n" + result + " " + code);
			done();
		});
	});
}