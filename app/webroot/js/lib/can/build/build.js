load("build/underscore.js");
var _ = this._;

load("steal/rhino/rhino.js");
steal('can/test/configuration.js',
	'steal/build/pluginify',
	'steal/build/amdify',
	'steal/generate/ejs.js',
	'can/build/settings.js',
function (testConfig, pluginify, amdify, EJS, libs) {
	// Use with ./js can/build/dist.js <outputfolder> <version> <library1> <library2>
	var version = _args[1] || 'edge';
	var libraries = _args[2] ? _args.slice(2) : _.keys(libs);
	var outFolder = (_args[0] || 'can/dist/') + version + '/';
	var render = function (from, to, data) {
		var text = readFile(from);

		var res = new EJS({
			text : text,
			name : from
		}).render(data);
		steal.File(to).save(res);
	}
	/**
	 * Build CanJS and test files for a given library
	 *
	 * @param lib
	 */
	var buildLibrary = function (lib) {
		var options = libs[lib],
			outFile = outFolder + '/can.' + lib,
			// testFile = testFolder + lib + '.html',
			buildFile = "can/build/make/" + lib + ".js",
			defaults = {
				out : outFile + '.js',
				onefunc : true,
				compress : false,
				skipAll : true
			};

		console.log('Building ' + lib + ' ' + version + ' to ' + outFile);
		steal.build.pluginify(buildFile, _.extend(defaults, options));
		steal.build.pluginify(buildFile, _.extend(defaults, options, {
			compress : true,
			out : outFile + '.min.js'
		}));

		// console.log('Creating distributable test HTML file ' + testFile);
		// render('can/build/templates/test.html.ejs', testFile, {
		//	name : testConfig[lib].name,
		//	dist : testConfig[lib].dist,
		//	version : version,
		//	type : lib
		// });
		// new steal.File('can/build/templates/qunit.js').copyTo(testFolder + '/qunit.js');
		// new steal.File('can/build/templates/qunit.css').copyTo(testFolder + '/qunit.css');
	};
	/**
	 * Build can.jquery-all.js with all plugins.
	 */
	var buildjQueryAll = function() {
		var options = libs.jquery,
			outFile = outFolder + '/can.jquery-all',
			buildFile = "can/build/make/all.js",
			defaults = {
				out : outFile + '.js',
				onefunc : true,
				compress : false,
				skipAll : true
			};

		console.log('Building ' + outFile + ' ' + version + ' to ' + outFile);
		steal.build.pluginify(buildFile, _.extend(defaults, options));
		steal.build.pluginify(buildFile, _.extend(defaults, options, {
			compress : true,
			out : outFile + '.min.js'
		}));
	};
	/**
	 * Build the AMD module distributable
	 */
	var buildAmd = function() {
		var excludes = [ "can/build/make/amd.js" ];
		_.each(_.values(libs), function(val) {
			excludes = excludes.concat(val.exclude);
		});
		steal.build.amdify('can/build/make/amd.js', {
			out: outFolder + '/amd',
			exclude: excludes,
			map : {
				'can/util' : 'can/util.js'
			}
		});
	};

	steal.File(outFolder).mkdirs();

	_.each(libraries, buildLibrary);
	buildAmd();
	buildjQueryAll();
});
