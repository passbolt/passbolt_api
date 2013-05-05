module.exports = function (grunt) {

  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-qunit-junit');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-saucelabs');

  grunt.initConfig({
    connect: {
      server: {
        options: {
          port: 80,
          base: 'js',
          keepalive: true
        }
      }
    },
	'qunit': {
		all: {
			options: {
		        urls: ['http://passbolt.local/js/lib/mad/funcunit.html']
			}
		}
    },
	'saucelabs-qunit': {
		all: {
			options: {
				username: 'cedricalfonsi',
				key: '82c85764-33b5-4ed7-ae2f-ae4e74268d10',
				tags: ['master'],
				testTimeout:1000000,
				testReadyTimeout:100000,
				detailedError:true,
				urls: ['http://passbolt.local/js/lib/mad/funcunit.html'],
				browsers: [{
					browserName: 'chrome'
				}]
			},
			onTestComplete: function(){
		        // Called after a qunit unit is done, per page, per browser
		        // Return true or false, passes or fails the test
		        // Returning undefined does not alter the test result
		
		        // For async return, call
		        var done = this.async();
		        setTimeout(function(){
		          // Return to this test after 1000 milliseconds
		          done(/*true or false changes the test result, undefined does not alter the result*/);
		        }, 1000);
		    }
		}
	},
  })

  // grunt.registerTask("server", "connect:server")
  grunt.registerTask('test', ['connect', 'qunit_junit', 'qunit']);
  grunt.registerTask('remote-test', ['saucelabs-qunit']);
};