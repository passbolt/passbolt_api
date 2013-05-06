module.exports = function (grunt) {

	grunt.loadNpmTasks('grunt-contrib-connect');
	grunt.loadNpmTasks('grunt-qunit-junit');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-saucelabs');

	var browsers = [{
		browserName: 'firefox',
		version: '19',
		platform: 'XP'
	}, {
		browserName: 'chrome',
		platform: 'XP'
	}, {
		browserName: 'chrome',
		platform: 'linux'
	}, {
		browserName: 'internet explorer',
		platform: 'WIN8',
		version: '10'
	}, {
		browserName: 'internet explorer',
		platform: 'VISTA',
		version: '9'
	}, {
		browserName: 'internet explorer',
		platform: 'VISTA',
		version: '8'
	}, {
		browserName: 'internet explorer',
		platform: 'XP',
		version: '7'
	}, {
		browserName: 'opera',
		platform: 'Windows 2008',
		version: '12'
	}];

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
			        urls: ['http://sauce.passbolt.local/js/lib/mad/funcunit.html']
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
					urls: ['http://sauce.passbolt.local/js/lib/mad/funcunit.html'],
					browsers: browsers
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
	});

	// grunt.registerTask("server", "connect:server")
	grunt.registerTask('test', ['connect', 'qunit_junit', 'qunit']);
	grunt.registerTask('remote-test', ['saucelabs-qunit']);
};