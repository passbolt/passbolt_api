steal("./view",function( $ ) {

	if ( window.steal ) {
		steal.type("view js", function( options, success, error ) {
			var type = can.view.types["." + options.type],
				id = can.view.toId(options.rootSrc);

			options.text = "steal('" + (type.plugin || "can/view/" + options.type) + "').then(function($){" + "can.view.preload('" + id + "'," + options.text + ");\n})";
			success();
		})
	}


	$.extend(can.view, {
		register: function( info ) {
			this.types["." + info.suffix] = info;

			if ( window.steal ) {
				steal.type(info.suffix + " view js", function( options, success, error ) {
					var type = can.view.types["." + options.type],
						id = can.view.toId(options.rootSrc+'');

					options.text = type.script(id, options.text)
					success();
				})
			}
		},
		registerScript: function( type, id, src ) {
			return "can.view.preload('" + id + "'," + $view.types["." + type].script(id, src) + ");";
		},
		preload: function( id, renderer ) {
			can.view.cached[id] = function( data, helpers ) {
				return renderer.call(data, data, helpers);
			};
		}

	});
	if ( window.steal ) {
		steal.type("view js", function( options, success, error ) {
			var type = can.view.types["." + options.type],
				id = can.view.toId(options.rootSrc+'');

			options.text = "steal('" + (type.plugin || "can/view/" + options.type) + "').then(function($){" + "can.view.preload('" + id + "'," + options.text + ");\n})";
			success();
		})
	}

});
