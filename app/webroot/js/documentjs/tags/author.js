steal.then(function() {
	/**
	 * @class DocumentJS.tags.author
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * Describes who the author of a class is.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /*
	 *  * @author Justin Meyer
	 *  * @author Brian Moschel
	 *  *|
	 * @codeend
	 */
	DocumentJS.tags.author = {
		add: function( line ) {
			var m = line.match(/^\s*@author\s*(.*)/)
			if ( m ) {
				this.author = m[1];
			}
		}
	};
})