steal.then(function() {
	/**
	 * @class DocumentJS.tags.iframe
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Adds an iframe to some page with example code.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /*
	 *  * @iframe jquery/view/view.html 700
	 *  *|
	 * @codeend
	 * 
	 * ###End Result:
	 * 
	 * @iframe jquery/view/view.html 700
	 */
	DocumentJS.tags.iframe = {
		add: function( line ) {
			var m = line.match(/^\s*@iframe\s*([\w\.\/]*)\s*([\w]*)\s*(.*)/)

			if ( m ) {
				var src = m[1] ? m[1].toLowerCase() : '';
				var height = m[2] ? m[2] : '320';
				this.comment += "<div class='iframe_wrapper' "
				this.comment += "data-iframe-src='" + src + "' "
				this.comment += "data-iframe-height='" + height + "'></div>";
			}
		}
	};
})