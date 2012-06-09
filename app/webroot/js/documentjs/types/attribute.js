steal.then(function() {
	/**
	 * @class DocumentJS.types.attribute
	 * @tag documentation
	 * @parent DocumentJS.types
	 * Documents an attribute.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 * * @@attribute delay
	 * * Sets the delay in milliseconds between an ajax request is made and
	 * * the success and complete handlers are called.  This only sets
	 * * functional fixtures.  By default, the delay is 200ms.
	 * *|
	 * $.fixture.delay = 200
	 * @codeend
	 * 
	 * You can see the end result [jQuery.fixture.delay | here].
	 */
	DocumentJS.Type("attribute",
	/**
	 * @Static
	 */
	{
	/*
	 * Checks if code matches the attribute type.
	 * @param {String} code
	 * @return {Boolean} true if code matches an attribute
	 */
		codeMatch: function( code ) {
			return code.match(/(\w+)\s*[:=]\s*/) && !code.match(/(\w+)\s*[:=]\s*function\(([^\)]*)/)
		},
	/*
	 * Must return the name if from the code.
	 * @param {String} code
	 * @return {Object} type data 
	 */
		code: function( code ) {
			var parts = code.match(/(\w+)\s*[:=]\s*/);
			if ( parts ) {
				return {
					name: parts[1]
				}
			}
		},
	/*
	 * Possible scopes for @attribute.
	 */
		parent: /static|proto|class|page/,
		useName: false
	});
})