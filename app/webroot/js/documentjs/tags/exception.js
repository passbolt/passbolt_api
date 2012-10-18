steal.then(function() {
	/**
	 * @class DocumentJS.tags.exception
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Says current class inherits from another class.
	 *
	 * ###Example:
	 * 
	 * @codestart
	 * /*
	 *  * @class Client
	 *  * @inherits Person
	 *  * ...
	 *  *|
	 *  var client = new Client() {
	 *  ...
	 * @codeend
	 */
	DocumentJS.tags.exception = {
		add: function( line ) {
			var m = line.match(/^\s*@\w+ ([\w\.\$]+)/)
			if ( m ) {
				if(typeof this.exceptions == 'undefined'){
					this.exceptions = [];
				}
				this.exceptions.push(m[1]);
			}
		}
	};
})