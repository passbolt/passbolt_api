steal.then(function() {
	/**
	 * @class DocumentJS.tags.codestart
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Starts a code block.  
	 * 
	 * Looks for "@codestart codeType".
	 *   
	 * Matches multiple lines.
	 *   
	 * Must end with "@codeend".
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 *
	 *  /* 
	 *   * @codestart
	 *   *  /* @class
	 *   *   * Person represents a human with a name.  Read about the 
	 *   *   * animal class [Animal | here].
	 *   *   * @constructor
	 *   *   * You must pass in a name.
	 *   *   * @param {String} name A person's name
	 *   *   *|
	 *   *   Person = function(name){
	 *   *      this.name = name
	 *   *      Person.count ++;
	 *   *   }
	 *   *  /* @Static *|
	 *   *  steal.Object.extend(Person, {
	 *   *      /* Number of People *|
	 *   *      count: 0
	 *   *  })
	 *   *  /* @Prototype *|
	 *   *  Person.prototype = {
	 *   *     /* Returns a formal name 
	 *   *      * @return {String} the name with "Mrs." added
	 *   *      *|
	 *   *      fancyName : function(){
	 *   *         return "Mrs. "+this.name;
	 *   *      }
	 *   *  }
	 *   * @codeend
	 *   *|
	 *
	 * @codeend 
	 */
	DocumentJS.tags.codestart = {
		add: function( line, last ) {
			var m = line.match(/^\s*@codestart\s*([\w-]*)\s*(.*)/)


			if ( m ) {
				return ["push", {
					type: m[1] ? m[1].toLowerCase() : 'javascript',
					lines: [],
					last: last,
					_last: this._last
				}];
			}

		},
		addMore: function( line, data ) {
			data.lines.push(line);
		}
	};
})