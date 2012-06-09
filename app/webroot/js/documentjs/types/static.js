steal.then(function() {
	/**
	 * @class DocumentJS.types.static
	 * @tag documentation
	 * @parent DocumentJS.types
	 * Sets the following functions and attributes to be added to Class or Constructor static (class) functions.
	 * 
	 * ###Example
	 * 
	 * @codestart
	 * $.Model.extend('Cookbook.Models.Recipe',
	 * /* @Static *|
	 * {
	 *  /**
	 *   * Retrieves recipes data from your backend services.
	 *   * @param {Object} params params that might refine your results.
	 *   * @param {Function} success a callback function that returns wrapped recipe objects.
	 *   * @param {Function} error a callback function for an error in the ajax request.
	 *   *|
	 *   findAll : function(params, success, error){
	 *      $.ajax({
	 *          url: '/recipe',
	 *          type: 'get',
	 *          dataType: 'json',
	 *          data: params,
	 *          success: this.callback(['wrapMany',success]),
	 *          error: error,
	 *          fixture: "//cookbook/fixtures/recipes.json.get" //calculates the fixture path from the url and type.
	 *      })
	 *    },
	 * ...
	 * @codeend
	 */
	DocumentJS.Type("static",
/*
 * @Static
 */
	{
	/*
	 * @return {Object} prototype data.
	 */
		code: function() {
			return {
				name: "static"
			}
		},
	/*
	 * Possible scopes for @static.
	 */
		parent: /class/,
		useName: true,
		hasChildren: true
	})
})