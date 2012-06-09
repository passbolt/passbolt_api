steal.then(function() {
	/**
	 * @class DocumentJS.types.function
	 * @tag documentation
	 * @parent DocumentJS.types
	 * Documents a function. Doc can guess at a functions name and params if the source following a comment matches something like:
	 * 
	 * @codestart
	 * myFuncOne : function(param1, param2){}  //or
	 * myFuncTwo = function(param1, param2){}  
	 * @codeend
	 * 
	 * ###Directives
	 *
	 * Use the following directives to document a function.
	 * 
	 * @codestart
	 * [ DocumentJS.types.function | @function ] functionName                       -&gt; Forces a function
	 * [ DocumentJS.tags.param | @param ] {optional:type} paramName Description -&gt; Describes a parameter
	 * [ DocumentJS.tags.return | @return ] {type} Description                    -&gt; Describes the return value
	 * @codeend
	 *
	 * ###Example
	 * 
	 * @codestart
	 * /* Adds, Mr. or Ms. before someone's name
	 *  * [ DocumentJS.tags.param | @param ] {String} name the persons name
	 *  * [ DocumentJS.tags.param | @param ] {optional:Boolean} gender true if a man, false if female.  Defaults to true.
	 *  * [ DocumentJS.tags.return | @return ] {String} returns the appropriate honorific before the person's name.
	 *  *|  
	 * honorific = function(name, gender){
	 * @codeend 
	 */
	DocumentJS.Type("function",
	/**
	 * @Static
	 */
	{
		codeMatch: /(?:([\w\.\$]+)|(["'][^"']+["']))\s*[:=]\s*function\s?\(([^\)]*)/,
	/*
	 * Parses the code to get the function data.
	 * Must return the name if from the code.
	 * @param {String} code
	 * @return {Object} function data
	 */
		code: function( code ) {
			var parts = this.codeMatch(code);

			if (!parts ) {
				parts = code.match(/\s*function\s+([\w\.\$]+)\s*(~)?\(([^\)]*)/)
			}
			var data = {};
			if (!parts ) {
				return;
			}
			data.name = parts[1] ? parts[1].replace(/^this\./, "").replace(/^\$./, "jQuery.") : parts[2];

			//clean up name if it has ""
			if (/^["']/.test(data.name) ) {
				data.name = data.name.substr(1, data.name.length - 2).replace(/\./g, "&#46;").replace(/>/g, "&gt;");
			}

			data.params = {};
			data.ret = {
				type: 'undefined',
				description: ""
			}
			var params = parts[3].match(/\w+/g);

			if (!params ) return data;

			for ( var i = 0; i < params.length; i++ ) {
				data.params[params[i]] = {
					description: "",
					type: "",
					optional: false,
					order: i,
					name: params[i]
				};
			}

			return data;
		},
	/*
	 * Possible scopes for @function.
	 */
		parent: /static|proto|class|page/,
		useName: false
	})
})