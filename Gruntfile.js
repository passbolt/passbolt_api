//var fs = require('fs');
var childProcess = require('child_process');

module.exports = function(grunt) {

	// ========================================================================
	// High level variables

	var config = {
	//	webroot			 : 'webroot',
		webroot : 'app/webroot',
		styleguide	 : 'passbolt_styleguide'
	}

	// ========================================================================
	// Configure task options

	grunt.initConfig({
		config : config,
		pkg: grunt.file.readJSON('package.json'),
		bower: grunt.file.readJSON('./.bowerrc'),
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
				'<%= config.webroot %>/js/lib/jscrollpane',
				'<%= config.webroot %>/js/lib/mad',
				'<%= config.webroot %>/js/lib/moment',
				'<%= config.webroot %>/js/lib/mousewheel',
				'<%= config.webroot %>/js/lib/passbolt_styleguide',
				'<%= config.webroot %>/js/lib/steal',
				'<%= config.webroot %>/js/lib/underscore',
				'<%= config.webroot %>/js/lib/xregexp',
				'<%= config.webroot %>/js/lib/jsSHA'
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
				command: '(cd ./app/webroot/js; ./js ./steal/buildjs ./app/passbolt.html)'
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
			}
		},
		copy: {
			styleguide : {
				files: [{
					// Fonts
					cwd: '<%= bower.directory %>/<%= config.styleguide %>/src/fonts',
					src: '*',
					dest: '<%= config.webroot %>/fonts',
					expand: true
				},{
					// Images for webroots (favicons, etc.)
					cwd: '<%= bower.directory %>/<%= config.styleguide %>/src/img/webroot',
					src: '*',
					dest: '<%= config.webroot %>',
					expand: true
				},{
					// Images
					cwd: '<%= bower.directory %>/<%= config.styleguide %>/src/img',
					src: ['default/**','logo/**','third_party/**','avatar/**','controls/**'],
					dest: '<%= config.webroot %>/img',
					expand: true
				},{
					// Less
					cwd: '<%= bower.directory %>/<%= config.styleguide %>/src/less',
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
				cwd: '<%= bower.directory %>',
				src: [
					'**',
					'!**passbolt_styleguide/**'
				],
				dest: '<%= config.webroot %>/js/lib/',
				expand: true
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

	// Run 'grunt test' to view lesslint recommendations
	grunt.registerTask('test', ['lesslint']);

	// Run 'grunt csslint' to check LESS quality, and if no errors then
	// compile LESS into CSS, combine and minify
	grunt.registerTask('csslint', ['lesslint', 'clean:css', 'less', 'cssmin']);

	// Run 'grunt css' to compile LESS into CSS, combine and minify
	grunt.registerTask('css', ['clean:css', 'less', 'cssmin']);

	// Bower styleguide deploy
	grunt.registerTask('styleguide-deploy', ['copy:styleguide']);
	// Bower libs deploy
	grunt.registerTask('lib-deploy', ['clean:lib', 'copy:lib', 'shell:mad_lib_patch']);

	// Run 'grunt production' to prepare the production release
	grunt.registerTask('production', ['clean:css', 'less', 'cssmin', 'clean:js', 'shell:jsmin']);

	// Build mad & all the demos apps to ensure that everything compile
	grunt.registerTask("build", ["steal-build"]);

	// 'grunt' will check code quality, and if no errors,
	// compile LESS to CSS, and minify and concatonate all JS and CSS
	grunt.registerTask('default', [ 'clean:css', 'less', 'cssmin']);

};
