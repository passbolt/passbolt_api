steal.then(function() {
	/**
	 * @class DocumentJS.tags.scope
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Forces the current type to start scope. 
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
     *  * @attribute convert
     *  * @scope
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
	 * 
	 * In the example above the use of @@scope forces __date__, __number__ and __boolean__ methods to be __convert's__ children.
	 */
	DocumentJS.tags.scope = {
		add: function( line ) {
			print("Scope! " + line)
			this.starts_scope = true;
		}
	};
})