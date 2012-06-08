steal.then(function() {
	/**
	 * @class DocumentJS.tags.alias
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * The Class or Constructor is known by another name.
	 * 
	 * ###Example:
	 *  
	 * @codestart
	 * /*
	 *  * @alias WidgetFactory
	 *  *|
	 *  $.Class.extend("jQuery.Controller",
	 *  ...
	 * @codeend 
	 */
	DocumentJS.tags.alias = {
		add: function( line ) {
			var m = line.match(/^\s*@alias\s*([\w\-\.]*)/)
			if ( m ) {
				this.alias = m[1];
			}
		}
	};
})