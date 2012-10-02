steal.then(function() {
	/**
	 * @class DocumentJS.tags.event
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
	DocumentJS.tags.event = {
		add: function( line ) {
			var m = line.match(/^\s*@\w+ ([\w\.\$]+)/)
			if ( m ) {
				if(typeof this.events == 'undefined'){
					this.events = [];
				}
				this.events.push(m[1]);
			}
		}
	};
})