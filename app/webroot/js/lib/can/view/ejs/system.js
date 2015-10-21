"format steal";
steal("can/view/ejs", function(can){

	function translate(load) {
		return "define(['can/view/ejs/ejs'],function(can){" +
			"return can.view.preloadStringRenderer('" + can.view.toId(load.metadata.pluginArgument) + "'," +
			'can.EJS(function(_CONTEXT,_VIEW) { ' + new can.EJS({
				text: load.source,
				name: load.name
			})
			.template.out + ' })' +
			")" +
			"})";
	}

	return {
		translate: translate
	};

});
