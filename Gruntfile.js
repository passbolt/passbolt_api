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
			css: [
				'<%= config.webroot %>/css/*.css', '!<%= config.webroot %>/css/cake.generic.css'
			],
			'js': [
				'<%= config.webroot %>/js/app/production.js'
			],
			'lib': [
				'<%= config.webroot %>/js/lib/can',
				'<%= config.webroot %>/js/lib/jquery',
				'<%= config.webroot %>/js/lib/jquery-ui',
				'<%= config.webroot %>/js/lib/mad',
				'<%= config.webroot %>/js/lib/moment',
				'<%= config.webroot %>/js/lib/jquery-mousewheel',
				'<%= config.webroot %>/js/lib/<%= config.styleguide %>',
				'<%= config.webroot %>/js/lib/steal',
				'<%= config.webroot %>/js/lib/underscore',
				'<%= config.webroot %>/js/lib/xregexp',
				'<%= config.webroot %>/js/lib/jssha'
			]
		},
		lesslint: {
			src: ['<%= config.webroot %>/less/*.less']
		},
		less: {
			files: {
				expand: true,
				flatten: true,
				cwd: "<%= config.webroot %>/less/",
				src: "*.less",
				dest: "<%= config.webroot %>/css/",
				ext: ".css"
			}
		},
		cssmin: {
			options: {
				banner: '/**!\n'+
						' * @name\t\t<%= pkg.name %>\n'+
						' * @version\t\tv<%= pkg.version %>\n' +
						' * @date\t\t<%= grunt.template.today("yyyy-mm-dd") %>\n' +
						' * @copyright\t<%= pkg.copyright %>\n' +
						' * @source\t\t<%= pkg.repository %>\n'+
						' * @license\t\t<%= pkg.license %>\n */\n',
				footer: '/* @license-end */'
			},
			minify: {
				expand: true,
				cwd: '<%= config.webroot %>/css/',
				src: ['*.css', '!*.min.css'],
				dest: '<%= config.webroot %>/css/',
				ext: '.min.css'
			}
		},
		shell: {
			jsmin: {
				options: {
					stderr: false
				},
				command: '(cd ./app/webroot/js; ./js ./lib/steal/buildjs ./app/passbolt.html)'
			},
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
					src: ['default/**','logo/**','third_party/**','avatar/**','controls/**'],
					dest: '<%= config.webroot %>/img',
					expand: true
				},{
					// Less
					cwd: '<%= config.modules_path %>/<%= config.styleguide %>/src/less',
					src: [
						'abstractions/**','base/**','components/**','dialogs/**',
						'pages/launching.less','pages/login.less','pages/passwords.less',
						'pages/users.less', 'pages/settings.less', 'pages/setup.less', 'setup.less',
						'pages/login.less', 'login.less', 'main.less', 'devel.less'
					],
					dest: '<%= config.webroot %>/less',
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
					cwd: '<%= config.modules_path %>/mad/',
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
				}]
			}
		},
		watch: {
			less: {
				files: ['Gruntfile.js', 'package.json', '<%= config.webroot %>/less/*.less','<%= config.webroot %>/less/**/*.less'],
				tasks: ['css'],
				options: {
					spawn: false
				}
			}
		},
		"steal-build": {
			default: {
				options: {
					system: {
						config: "./app/webroot/js/stealconfig.js",
						main: "app/passbolt"
					},
					buildOptions: {
						minify: false
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

	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.loadNpmTasks('grunt-contrib-jshint');

	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.loadNpmTasks('grunt-contrib-concat');

	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.loadNpmTasks('grunt-lesslint');

	grunt.loadNpmTasks('grunt-contrib-less');

	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.loadNpmTasks('grunt-shell');

	grunt.loadNpmTasks('grunt-contrib-copy');

	grunt.loadNpmTasks("steal-tools");

	// ========================================================================
	// Register Tasks

	// Run 'grunt csslint' to check LESS quality, and if no errors then
	// compile LESS into CSS, combine and minify
	grunt.registerTask('csslint', ['lesslint', 'css']);

	// Run 'grunt css' to compile LESS into CSS, combine and minify
	grunt.registerTask('css', ['clean:css', 'less', 'cssmin']);

	// Npm styleguide deploy
	grunt.registerTask('styleguide-deploy', ['shell:updatestyleguide','copy:styleguide','css']);

	// Npm libs deploy
	grunt.registerTask('lib-deploy', ['clean:lib', 'copy:lib', 'shell:mad_lib_patch']);

	// Run 'grunt js' to prepare the javascript
	grunt.registerTask('js', ['clean:js', 'shell:jsmin']);

	// Run 'grunt production' to prepare the production release
	grunt.registerTask('production', ['css', 'clean:js', 'shell:jsmin']);

	// Build mad & all the demos apps to ensure that everything compile
	grunt.registerTask("build", ["steal-build"]);

	// 'grunt' will check code quality, and if no errors,
	// compile LESS to CSS, and minify and concatonate all JS and CSS
	grunt.registerTask('default', ['css']);

};
