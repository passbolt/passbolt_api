load("build/underscore.js");
var _ = this._;

load("steal/rhino/rhino.js");
steal('steal/build/pluginify', 'steal/build/amdify', function () {
	// Use with ./js can/build/dist.js <outputfolder> <version> <library1> <library2>
	var version = _args[1] || 'edge';
	var outFolder = (_args[0] || 'jquery/dist/') + version + '/';
	var outFile = outFolder + 'jquerypp';
	var buildFile = 'jquery/build/lib.js';
	var options = {
		exclude : ["jquery", "jquery/jquery.js", "jquery/build/lib.js"],
		wrapInner : ['(function(window, $, undefined) {\n', '\n})(this, jQuery);']
	};

	/**
	 * Build jQuery++
	 */
	var build = function () {
		var defaults = {
			out : outFile + '.js',
			onefunc : true,
			compress : false,
			skipAll : true
		};

		steal.build.pluginify(buildFile , _.extend(defaults, options));
		steal.build.pluginify(buildFile, _.extend(defaults, options, {
			compress : true,
			out : outFile + '.min.js'
		}));
	};

	/**
	 * Build the AMD module distributable
	 */
	var buildAmd = function () {
		steal.build.amdify(buildFile, {
			out : outFolder + '/amd',
			exclude : options.exclude,
			map : {
				'jquery/' : 'jquerypp/'
			}
		});
	};

	steal.File(outFolder).mkdirs();

	build();
	buildAmd();
});
