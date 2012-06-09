steal.then(function() {
	/**
	 * @class DocumentJS.tags.page
	 * @parent DocumentJS.tags
	 */
	DocumentJS.tags.page = {
		add: function( line ) {
			var m = line.match(/^\s*@\w+\s+([^\s]+)\s+(.+)/)
			if ( m ) {
				this.name = m[1];
				this.title = m[2] || this.name;
			}
		}
	};
})