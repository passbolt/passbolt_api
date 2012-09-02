steal.then(function() {
	/**
	 * @class DocumentJS.tags.see
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
	DocumentJS.tags.see = {
		add: function( line ) {
			var m = line.match(/^\s*@\w+ ([\w\.\$]+)/)
			if ( m ) {
				if(typeof this.sees == 'undefined'){
					this.sees = [];
				}
				this.sees.push(m[1]);
			}
		}
	};
})