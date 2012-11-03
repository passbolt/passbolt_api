
load("steal/rhino/rhino.js");
steal('steal/build/pluginify', 'jquery/build/extract.js', 'jquery/build/stealify.js', // 'jquery/build/amdify.js',
function() {

	var out = "jquery/dist/",
		excludes = [ 'steal/dev/',
			'jquery',
			'jquery/build/lib.js',
			'stealconfig.js'
		];

	steal.File(out).mkdirs();

	// Create full library
//	steal.build.pluginify('jquery/build/lib.js', {
//		out: out + "jquerypp.js",
//		skipCallbacks: true,
//		shim : { 'jquery' : 'jQuery' },
//		exclude : excludes.concat([
//			'jquery/dom/dom.js', 'jquery/event/event.js', 'jquery/jquery.js'
//		])
//	});

	// Make Steal distributable
	steal.build.stealify('jquery/build/lib.js', {
		out : out + 'steal/',
		exclude : excludes
	});

//	// Create separate files
//	steal.build.extract({
//		"jquery/dom/animate/animate.js" : "jquery.animate.js",
//		"jquery/dom/compare/compare.js" : "jquery.compare.js",
//		"jquery/dom/cookie/cookie.js" : "jquery.cookie.js",
//		"jquery/dom/dimensions/dimensions.js" : "jquery.dimensions.js",
//		"jquery/dom/form_params/form_params.js" : "jquery.form_params.js",
//		"jquery/dom/range/range.js" : "jquery.range.js",
//		"jquery/dom/selection/selection.js" : "jquery.selection.js",
//		"jquery/dom/styles/styles.js" : "jquery.styles.js",
//		"jquery/dom/within/within.js" : "jquery.within.js",
//		"jquery/event/default/default.js" : "jquery.event.default.js",
//		"jquery/event/destroyed/destroyed.js" : "jquery.event.destroyed.js",
//		"jquery/event/drag/drag.js" : "jquery.event.drag.js",
//		"jquery/event/drop/drop.js" : "jquery.event.drop.js",
//		"jquery/event/fastfix/fastfix.js" : "jquery.event.fastfix.js",
//		"jquery/event/hover/hover.js" : "jquery.event.hover.js",
//		"jquery/event/key/key.js" : "jquery.event.key.js",
//		"jquery/event/pause/pause.js" : "jquery.event.pause.js",
//		"jquery/event/resize/resize.js" : "jquery.event.resize.js",
//		"jquery/event/swipe/swipe.js" : "jquery.event.swipe.js",
//		"jquery/event/livehack/livehack.js" : "jquery.event.livehack.js",
//		"jquery/lang/json/json.js" : "jquery.lang.json.js",
//		"jquery/lang/vector/vector.js" : "jquery.lang.vector.js"
//	}, {
//		skipCallbacks: true,
//		exclude : excludes.concat([
//			'jquery/dom/dom.js', 'jquery/event/event.js', 'jquery/jquery.js'
//		]),
//		out : out + 'lib/'
//	});

	// Make AMD modules
//	steal.build.amdify('jquery/build/lib.js', {
//		out : out + 'amd/',
//		exclude : excludes.concat([
//			'jquery/dom/dom.js', 'jquery/event/event.js'
//		]),
//		map : { // steal file to CommonJS module name mappings
//			"jquery/jquery.js" : "jquery",
//			"jquery/build/lib.js" : "jquerypp/index",
//			"jquery/lang/json/json.js" : "jquerypp/util/json",
//			"jquery/lang/vector/vector.js" : "jquerypp/util/vector",
//			"jquery/dom/animate/animate.js" : "jquerypp/animate",
//			"jquery/dom/compare/compare.js" : "jquerypp/compare",
//			"jquery/dom/cookie/cookie.js" : "jquerypp/cookie",
//			"jquery/dom/dimensions/dimensions.js" : "jquerypp/dimensions",
//			"jquery/dom/form_params/form_params.js" : "jquerypp/form_params",
//			"jquery/dom/range/range.js" : "jquerypp/range",
//			"jquery/dom/selection/selection.js" : "jquerypp/selection",
//			"jquery/dom/styles/styles.js" : "jquerypp/styles",
//			"jquery/dom/within/within.js" : "jquerypp/within",
//			"jquery/event/default/default.js" : "jquerypp/event/default",
//			"jquery/event/destroyed/destroyed.js" : "jquerypp/event/destroyed",
//			"jquery/event/drag/drag.js" : "jquerypp/event/drag",
//			"jquery/event/drag/limit/limit.js" : "jquerypp/event/drag.limit",
//			"jquery/event/drag/scroll/scroll.js" : "jquerypp/event/drag.scroll",
//			"jquery/event/drag/step/step.js" : "jquerypp/event/drag.step",
//			"jquery/event/drop/drop.js" : "jquerypp/event/drop",
//			"jquery/event/fastfix/fastfix.js" : "jquerypp/event/fastfix",
//			"jquery/event/hover/hover.js" : "jquerypp/event/hover",
//			"jquery/event/key/key.js" : "jquerypp/event/key",
//			"jquery/event/reverse/reverse.js" : "jquerypp/event/reverse",
//			"jquery/event/livehack/livehack.js" : "jquerypp/event/livehack",
//			"jquery/event/pause/pause.js" : "jquerypp/event/pause",
//			"jquery/event/resize/resize.js" : "jquerypp/event/resize",
//			"jquery/event/swipe/swipe.js" : "jquerypp/event/swipe"
//		},
//		names : { // Module name to variable name mappings
//			'jquery' : 'jQuery'
//		},
//		global : 'jQuery'
//	});
});
