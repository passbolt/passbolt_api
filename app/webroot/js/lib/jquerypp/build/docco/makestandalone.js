load("steal/rhino/rhino.js");
steal('jquery/build/extract.js', 'steal/clean', function() {

	var out = "jquery/build/docco/standalone/",
		excludes = [ 'steal/dev',
			"can/util/jquery/jquery.1.7.1.js",
			"jquery/build/lib.js" ],
		plugins = {
			"jquery/dom/animate/animate.js" : "jquery.animate.js",
			"jquery/dom/compare/compare.js" : "jquery.compare.js",
			"jquery/dom/cookie/cookie.js" : "jquery.cookie.js",
			"jquery/dom/dimensions/dimensions.js" : "jquery.dimensions.js",
			"jquery/dom/form_params/form_params.js" : "jquery.form_params.js",
			"jquery/dom/range/range.js" : "jquery.range.js",
			"jquery/dom/selection/selection.js" : "jquery.selection.js",
			"jquery/dom/styles/styles.js" : "jquery.styles.js",
			"jquery/dom/within/within.js" : "jquery.within.js",
			"jquery/event/default/default.js" : "jquery.event.default.js",
			"jquery/event/destroyed/destroyed.js" : "jquery.event.destroyed.js",
			"jquery/event/drag/drag.js" : "jquery.event.drag.js",
			"jquery/event/drop/drop.js" : "jquery.event.drop.js",
			"jquery/event/fastfix/fastfix.js" : "jquery.event.fastfix.js",
			"jquery/event/hover/hover.js" : "jquery.event.hover.js",
			"jquery/event/key/key.js" : "jquery.event.key.js",
			"jquery/event/pause/pause.js" : "jquery.event.pause.js",
			"jquery/event/resize/resize.js" : "jquery.event.resize.js",
			"jquery/event/swipe/swipe.js" : "jquery.event.swipe.js",
			"jquery/event/livehack/livehack.js" : "jquery.event.livehack.js",
			"jquery/lang/json/json.js" : "jquery.lang.json.js",
			"jquery/lang/vector/vector.js" : "jquery.lang.vector.js"
		};

	steal.File(out).mkdirs();

	steal.build.extract(plugins, {
		skipCallbacks: true,
		exclude : excludes.concat([
			'jquery/dom/dom.js', 'jquery/event/event.js', 'jquery/jquery.js'
		]),
		out : out
	});

	for(var name in plugins) {
		console.log("Cleaning " + out + plugins[name]);
		steal.clean(out + plugins[name]);
	}
});
