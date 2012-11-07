module.exports = function( grunt ) {


	/** /
	grunt.initConfig({
		"closure-compiler": {
frontend: {
      js: 'static/src/frontend.js',
      jsOutputFile: 'static/js/frontend.min.js',
      options: {
        compilation_level: 'ADVANCED_OPTIMIZATIONS',
        language_in: 'ECMASCRIPT5_STRICT'
      }
    }
		}
	
	});
	/**/

	grunt.registerTask("filesize", "Builds and Minifies CanJS, then outputs filesize information", function() {
		this.async();
		grunt.log.write("DONE");
		done();
	});

};
