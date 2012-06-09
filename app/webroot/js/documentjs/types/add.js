steal.then(function() {
	/**
	 * @class DocumentJS.types.add
	 * @tag documentation
	 * @parent DocumentJS.types
	 * Used to set scope to add to classes or methods in another file. 
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 * * @add jQuery.String.static
	 * *|
	 * $.String.
	 * /**
	 * * Splits a string with a regex correctly cross browser
	 * * @param {Object} string
	 * * @param {Object} regex
	 * *|
	 * rsplit = function( string, regex ) {
	 * @codeend
	 * 
	 * It's important to note that add must be in its own comment block.
	 * 
	 * ###End Result:
	 * 
	 * @image jmvc/images/add_tag_example.png 970
	 */
	DocumentJS.Type("add",
	/**
	 * @Static
	 */
	{
		/**
		 * Code parser.
		 */
		code: function() {

		},
		/**
		 * @constructor
		 * @param {Object} type data
		 */
		init: function( props ) {
			if (!DocumentJS.objects[props.name] ) {
				DocumentJS.objects[props.name] = props;
			}
			return DocumentJS.objects[props.name];
		},
	/*
	 * Possible scopes for @add.
	 */
		useName: true,
		hasChildren: true
	})
})