steal('jquery/controller',function(){

/**
 * @tag home
 * 
 * Handles iframe menu events
 */
jQuery.Controller('Iframe',
/* @Static */
{},
/* @Prototype */
{
	init: function() {
		var self = this;
		var height = 320,
			html = "",
			source = "";
		var scripts = [];

		this.element.html("//documentjs/jmvcdoc/views/iframe/init.ejs", {});

		var src = steal.root.join(this.element.attr("data-iframe-src"));
		height = !this.element.attr("data-iframe-height") ? height : this.element.attr("data-iframe-height");
		var $iframe = this.find("iframe");
		$iframe.attr("src", src);
		$iframe.attr("height", height);

		$iframe.bind('load', function() {
			$('script', $iframe[0].contentWindow.document).each(function( i, script ) {
				if (!script.text.match(/steal.end()/) ) scripts.push(script);
			});
			if (!self.iframesCache ) self.iframesCache = {};
			self.iframesCache[self.toId($iframe.attr("src"))] = scripts;
		});
	},

	toId: function( src ) {
		return src.replace(/[\/\.]/g, "_")
	},

	".iframe_menu_button click": function( el, ev ) {
		var $iframe = this.find("iframe");
		var id = this.toId($iframe.attr("src"));
		var scripts = this.iframesCache[id];
		if ( scripts && scripts.length > 0 ) {
			var $iframeMenuWrapper = $(".iframe_menu_wrapper");
			if (!$iframeMenuWrapper.length ) {
				el.after("//jmvcdoc/views/iframe/menu.ejs", {
					'scripts': scripts,
					'iframeWindow': $iframe[0].contentWindow
				}, DocumentationController.Helpers);

				$iframeMenuWrapper = $(".iframe_menu_wrapper");
				$iframeMenuWrapper.mxui_layout_positionable({
					my: 'right top',
					at: 'right bottom'
				}).trigger('move', el);

				$iframeMenuItem = $(".iframe_menu_item a");
				$iframeMenuItem.bind("click", function( ev ) {
					var src = steal.root.join($(this).attr("data-src"));
					window.open(src, src);
				})

			} else {
				$iframeMenuWrapper.slideToggle("slow");
			}

		}
	},

	windowresize: function( el, ev ) {
		$(".iframe_menu_wrapper").trigger('move', $(".iframe_menu_button"));
	}
});
})
