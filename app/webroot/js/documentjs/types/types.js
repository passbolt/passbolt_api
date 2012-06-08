
steal.then(function() {
	/**
	 * @attribute DocumentJS.types
	 * @parent DocumentJS
	 * Type directives represent every possible javascript construct 
	 * you might want to document.
	 * 
	 * ## How to create your own type directive
	 * 
     * All you have to do is create a file describing what your new directive tag looks like and does, 
     * and just drop it into the "tags/types" directory - it's that easy.
     *
     * A common documentation need in JavaScript projects is to document classes with non-standard syntax. 
     * Popular frameworks handle class creation using the following pattern:
     * 
     * @codestart
     * /* 
     *  * we want to document this as being a class 
     *  *|
     * var Person = makeClass(
     * {
     *   initialize: function(name) {
     *       this.name = name;
     *   },
     *   say: function(message) {
     *       return this.name + " says: " + message;
     *   }
     * });
     * @codeend
     *  
     * Documentjs is flexible enough to let you do this with minimal effort. 
     * All you have to do is to add a new type to the existing types folder (__documentjs/types__).  Let's name it __make_class.js__:
     *
     * @codestart
	 * DocumentJS.Type("MakeClass",
	 * /**
	 *  * @@Static
	 *  *|
	 * {
	 *     codeMatch: /(\w+)\s*[:=]\s*makeClass\(([^\)]*)/),
	 *     /*
	 *      * Parses the code to get the class data.
	 *      * @@param {String} code
	 *      * @@return {Object} class data
	 *      *|
	 *      code: function( code ) {
	 *          var parts = code.match(this.codeMatch);
	 *              if ( parts ) {
	 *                  return {
	 *                      name: parts[2],
	 *                      inherits: parts[1].replace("$.", "jQuery.")
     *                      type: "class"
	 *                  }
     *              }
	 *      }
     * }
     * @codeend
     * 
     * There's one final step you must follow to make your custom type work: add it to the list of 
     * loaded types in __documentjs/types/types.js__.
     * 
     * @codestart
     *  ...
     * '//documentjs/types/function', '//documentjs/types/page', '//documentjs/types/prototype',
     * '//documentjs/types/script', '//documentjs/types/static', '//documentjs/types/make_class');
     * @codeend
     * 
     * And that's it! Now you can write your code using your favorite framework 
     * and know that all your classes will be documented correctly for you.
     * 
     * 
	 */
	DocumentJS.types = {};
},'documentjs/types/type.js')
.then('documentjs/types/add.js')
.then('documentjs/types/attribute.js')
.then('documentjs/types/class.js')
.then('documentjs/types/function.js')
.then('documentjs/types/page.js')
.then('documentjs/types/prototype.js')
.then('documentjs/types/script.js')
.then('documentjs/types/static.js');