//var fs = require('fs');
var childProcess = require('child_process');

module.exports = function(grunt) {

	// ========================================================================
	// High level variables

	var config = {
		webroot : 'app/webroot',
		styleguide : 'passbolt-styleguide',
		modules_path : 'node_modules'
	};

	// ========================================================================
	// Configure task options

	grunt.initConfig({
		config : config,
		pkg: grunt.file.readJSON('package.json'),
		clean: {
			'lib': [
				'<%= config.webroot %>/js/lib/can',
				'<%= config.webroot %>/js/lib/jquery',
				'<%= config.webroot %>/js/lib/jquery-ui',
				'<%= config.webroot %>/js/lib/mad',
				'<%= config.webroot %>/js/lib/moment',
				'<%= config.webroot %>/js/lib/moment-timezone',
				'<%= config.webroot %>/js/lib/jquery-mousewheel',
				'<%= config.webroot %>/js/lib/<%= config.styleguide %>',
				'<%= config.webroot %>/js/lib/steal',
				'<%= config.webroot %>/js/lib/underscore',
				'<%= config.webroot %>/js/lib/xregexp',
				'<%= config.webroot %>/js/lib/jssha',
				'<%= config.webroot %>/js/lib/urijs',
				'<%= config.webroot %>/js/lib/semver'
			]
		},
		shell: {
			mad_lib_patch: {
				options: {
					stderr: false
				},
				command: [
					'(cd ./app/webroot/js/lib/can; patch -p1 < ../mad/patches/can-system_preload_template.patch;)',
					'(cd ./app/webroot/js/lib/can; patch -p1 < ../mad/patches/can-util_string_get_object_set_object.patch;)'
					//'(cd ./node_modules/documentjs; patch -p1 < ./app/webroot/js/lib/mad/patches/patches/documentjs-demo_tag_url_and_sharp.patch;)'
				].join('&&')
			},
			updatestyleguide: {
				options: {
					stderr: false
				},
				command: 'rm -rf <%= config.modules_path %>/<%= config.styleguide %>; npm install'
			}
		},
		copy: {
			styleguide : {
				files: [{
					// Fonts
					cwd: '<%= config.modules_path %>/<%= config.styleguide %>/src/fonts',
					src: '*',
					dest: '<%= config.webroot %>/fonts',
					expand: true
				},{
					// Images for webroots (favicons, etc.)
					cwd: '<%= config.modules_path %>/<%= config.styleguide %>/src/img/webroot',
					src: '*',
					dest: '<%= config.webroot %>',
					expand: true
				},{
					// Images
					cwd: '<%= config.modules_path %>/<%= config.styleguide %>/src/img',
					src: ['default/**','logo/**','third_party/**','avatar/**','controls/**', 'illustrations/nest.png'],
					dest: '<%= config.webroot %>/img',
					expand: true
				},{
					// Less
					cwd: '<%= config.modules_path %>/<%= config.styleguide %>/build/css',
					src: ['devel.min.css', 'login.min.css', 'main.min.css', 'setup.min.css'],
					dest: '<%= config.webroot %>/css',
					expand: true
				}]
			},
			lib : {
				files: [{
					// steal
					cwd: '<%= config.modules_path %>/steal/',
					src: ['steal.js', 'steal.production.js', 'ext/**', 'src/**'],
					dest: '<%= config.webroot %>/js/lib/steal/',
					nonull: true,
					expand: true
				}, {
					// canjs
					cwd: '<%= config.modules_path %>/can/',
					src: ['*/**', 'can.js'],
					dest: '<%= config.webroot %>/js/lib/can/',
					nonull: true,
					expand: true
				}, {
					// mad
					cwd: '<%= config.modules_path %>/passbolt-mad/',
					src: ['src/**', 'patches/**'],
					dest: '<%= config.webroot %>/js/lib/mad/',
					nonull: true,
					expand: true
				}, {
					// Jquery
					cwd: '<%= config.modules_path %>/jquery/',
					src: 'dist/jquery.js',
					dest: '<%= config.webroot %>/js/lib/jquery/',
					nonull: true,
					expand: true
				}, {
					// moment
					cwd: '<%= config.modules_path %>/moment/',
					src: ['locale/**', 'moment.js'],
					dest: '<%= config.webroot %>/js/lib/moment/',
					nonull: true,
					expand: true
				}, {
					// moment-timezone
					cwd: '<%= config.modules_path %>/moment-timezone/',
					src: ['builds/moment-timezone-with-data.js'],
					dest: '<%= config.webroot %>/js/lib/moment-timezone/',
					nonull: true,
					expand: true
				}, {
					// jquery-mousewheel
					cwd: '<%= config.modules_path %>/jquery-mousewheel/',
					src: 'jquery.mousewheel.js',
					dest: '<%= config.webroot %>/js/lib/jquery-mousewheel/',
					nonull: true,
					expand: true
				}, {
					// underscore
					cwd: '<%= config.modules_path %>/underscore/',
					src: 'underscore.js',
					dest: '<%= config.webroot %>/js/lib/underscore/',
					nonull: true,
					expand: true
				}, {
					// xregexp
					cwd: '<%= config.modules_path %>/xregexp/',
					src: 'xregexp-all.js',
					dest: '<%= config.webroot %>/js/lib/xregexp/',
					nonull: true,
					expand: true
				}, {
					// jssha
					cwd: '<%= config.modules_path %>/jssha/',
					src: 'src/**',
					dest: '<%= config.webroot %>/js/lib/jssha/',
					nonull: true,
					expand: true
				}, {
					// urijs
					cwd: '<%= config.modules_path %>/urijs/',
					src: 'src/**',
					dest: '<%= config.webroot %>/js/lib/urijs/',
					nonull: true,
					expand: true
				}, {
					// semver
					cwd: '<%= config.modules_path %>/semver/',
					src: 'semver.js',
					dest: '<%= config.webroot %>/js/lib/semver/',
					nonull: true,
					expand: true
				}]
			}
		},
		"steal-build": {
			app: {
				options: {
					system: {
						config: "./app/webroot/js/stealconfig.js",
						main: "app/passbolt"
					},
					buildOptions: {
						minify: true
					}
				}
			},
			login: {
				options: {
					system: {
						config: "./app/webroot/js/stealconfig.js",
						main: "app/login"
					},
					buildOptions: {
						minify: true
					}
				}
			}
		}
	});

	// on watch events configure jshint:all to only run on changed file
	//    grunt.event.on('watch', function(action, filepath) {
	//        grunt.config(['jshint', 'all'], filepath);
	//    });

	// ========================================================================
	// Initialise

	grunt.loadNpmTasks('grunt-contrib-jshint');

	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.loadNpmTasks('grunt-contrib-concat');

	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.loadNpmTasks('grunt-shell');

	grunt.loadNpmTasks('grunt-contrib-copy');

	grunt.loadNpmTasks("steal-tools");

	// ========================================================================
	// Register Tasks

	// Npm styleguide deploy
	grunt.registerTask('styleguide-update', ['shell:updatestyleguide','copy:styleguide']);

	// Npm libs deploy
	grunt.registerTask('lib-deploy', ['clean:lib', 'copy:lib', 'shell:mad_lib_patch']);

	// Build mad & all the demos apps to ensure that everything compile
	grunt.registerTask("build", ["steal-build:app", "steal-build:login"]);
	grunt.registerTask("build-app", ["steal-build:app"]);
	grunt.registerTask("build-login", ["steal-build:login"]);

	// 'grunt' default
	grunt.registerTask('default', ["steal-build:app", "steal-build:login"]);

};
