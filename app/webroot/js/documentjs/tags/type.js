steal.then(function() {
	/**
	 * @class DocumentJS.tags.type
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Sets the type for the current commented code.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 *  *
     *  * @attribute convert
     *  * @type Object
	 *  * An object of name-function pairs that are used to convert attributes.
	 *  * Check out [jQuery.Model.static.attributes]
	 *  * for examples.
	 *  *|
	 *  convert: {
	 *      "date": function( str ) {
	 *          return typeof str == "string" ? (Date.parse(str) == NaN ? null : Date.parse(str)) : str
	 *      },
	 *      "number": function( val ) {
	 *          return parseFloat(val)
	 *      },
	 *      "boolean": function( val ) {
	 *          return Boolean(val)
	 *      }
	 *  }
	 * @codeend 
	 */
	DocumentJS.tags.type = {
		add: function( line ) {
			var m = line.match(/^\s*@type\s*([\w\.\/\$]*)/)
			if ( m ) {
				this.attribute_type = m[0]
			}
		}
	};
})