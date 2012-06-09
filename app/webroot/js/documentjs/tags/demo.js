steal.then(function() {
	/**
	 * @class DocumentJS.tags.demo
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Placeholder for an application demo.
	 * 
	 * ###Demo Example:
	 * 
	 * @codestart
	 * /*
	 *  * @demo jquery/controller/controller.html
	 *  *|
	 * @codeend
	 * 
	 * ###End Result:
	 *   
	 * @demo jquery/controller/controller.html
	 */
	DocumentJS.tags.demo = {
		add: function( line ) {
			var m = line.match(/^\s*@demo\s*([\w\.\/\-\$]*)\s*([\w]*)/)
			if ( m ) {
				var src = m[1] ? m[1].toLowerCase() : '';
				this.comment += "<div class='demo_wrapper' data-demo-src='" + src + "'></div>";
			}
		}
	};
})