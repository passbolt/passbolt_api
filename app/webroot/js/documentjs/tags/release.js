steal(function() {
	/**
	 * @class DocumentJS.tags.release
	 * @tag documentation
	 * @release 3.3
	 * @parent DocumentJS.tags 
	 * Specifies the relase
	 *
	 */
	return {
		add: function( line ) {
			var m = line.match(/^\s*@release\s+(\S*)\s*$/);
			if ( m ) {
				this.release = m[1];
			}
		}
	};
})

