module.exports = function (grunt) {

	var _ = grunt.utils._;
	var outFiles = {
		edge : '<%= meta.out %>/edge/**/*.js',
		latest : '<%= meta.out %>/<%= pkg.version %>/**/*.js',
	};

	grunt.initConfig({
		pkg : '<json:package.json>',
		meta : {
			out : "dist/",
			beautifier : {
				options : {
					indentSize : 1,
					indentChar : "\t"
				},
				exclude : [/\.min\./, /qunit\.js/]
			},
			banner : '/*\n* <%= pkg.title || pkg.name %> - <%= pkg.version %> ' +
				'(<%= grunt.template.today("yyyy-mm-dd") %>)\n' +
				'<%= pkg.homepage ? "* " + pkg.homepage + "\n" : "" %>' +
				'* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>\n' +
				'* Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %>\n*/'
		},
		beautifier : {
			codebase : '<config:meta.beautifier>',
			dist : '<config:meta.beautifier>'
		},
		beautify : {
			codebase : [
				'construct/**/*.js',
				'control/**/*.js',
				'model/**/*.js',
				'observe/**/*.js',
				'route/**/*.js',
				'test/**/*.js',
				'util/**/*.js'
			],
			dist : '<%= meta.out %>/**/*.js'
		},
		build : {
			edge : {
				src : "can/build/build.js",
				out : 'can/<%= meta.out %>'
			},
			edgePlugins : {
				src : "can/build/plugins.js",
				out : 'can/<%= meta.out %>'
			},
			latest : {
				src : "can/build/build.js",
				version : '<%= pkg.version %>',
				out : 'can/<%= meta.out %>'
			},
			latestPlugins : {
				src : "can/build/plugins.js",
				version : '<%= pkg.version %>',
				out : 'can/<%= meta.out %>'
			}
		},
		shell : {
			bundleLatest : 'cd <%= meta.out %> && zip -r can.js.<%= pkg.version %>.zip <%= pkg.version %>/',
			getGhPages : 'git clone -b gh-pages <%= pkg.repository.url %> build/gh-pages',
			copyLatest : 'rm -rf build/gh-pages/release/<%= pkg.version %> && ' +
				'cp -R <%= meta.out %>/<%= pkg.version %> build/gh-pages/release/<%= pkg.version %> && ' +
				'rm -rf build/gh-pages/release/latest && ' +
				'cp -R <%= meta.out %>/<%= pkg.version %> build/gh-pages/release/latest',
			copyEdge : 'rm -rf build/gh-pages/release/edge && ' +
				'cp -R <%= meta.out %>/edge build/gh-pages/release/edge',
			updateGhPages : 'cd build/gh-pages && git add . --all && git commit -m "Updating release (latest: <%= pkg.version %>)" && ' +
				'git push origin',
			cleanup : 'rm -rf build/gh-pages',
			_options : {
				stdout : true,
				failOnError : true
			}
		},
		downloads : '<json:build/downloads.json>',
		docco : _.extend({_options : {
				exclude : [/\.min\./, /\/amd/, /qunit\.js/]
			}
		}, outFiles),
		strip : outFiles,
		bannerize : outFiles
	});

	grunt.loadTasks("../build/tasks");

	grunt.registerTask("edge", "build:edge build:edgePlugins strip:edge beautify:dist bannerize:edge");
	grunt.registerTask("latest", "build:latest build:latestPlugins strip:latest beautify:dist bannerize:latest docco:latest");
	grunt.registerTask("ghpages", "shell:cleanup shell:getGhPages shell:copyLatest shell:updateGhPages shell:cleanup");
	grunt.registerTask("deploy", "latest ghpages shell:bundleLatest downloads");
};
