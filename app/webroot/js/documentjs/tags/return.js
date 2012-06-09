steal.then(function() {
	/**
	 * @class DocumentJS.tags.return
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Describes return data in the format.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 *  /**
	 *   * Capitalizes a string
	 *   * @param {String} s the string to be lowercased.
	 *   * @return {String} a string with the first character capitalized, and everything else lowercased
	 *   *|
	 *   capitalize: function( s, cache ) {
	 *       return s.charAt(0).toUpperCase() + s.substr(1);
	 *   }
	 * @codeend
	 * 
	 * ###End Result:
	 * 
	 * @image jmvc/images/return_tag_example.png
	 */
	DocumentJS.tags["return"] = {
		add: function( line ) {
			if (!this.ret ) {
				this.ret = {
					type: 'undefined',
					description: ""
				}
			}

			var parts = line.match(/\s*@return\s+(?:\{([\w\|\.\/]+)\})?\s*(.*)?/);

			if (!parts ) {
				return;
			}

			var description = parts.pop() || "";
			var type = parts.pop();
			this.ret = {
				description: description,
				type: type
			};
			return this.ret;
		},
		addMore: function( line ) {
			this.ret.description += "\n" + line;
		}
	};
})