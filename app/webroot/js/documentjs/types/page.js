steal.then(function() {
	/**
	 * @class DocumentJS.types.page
	 * @tag documentation
	 * @parent DocumentJS.types
	 * 
	 * Defines a standalone documentation page.
	 * 
	 * Used to organize your documentation in a flexible manner.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 *  * @page follow Follow JavaScriptMVC
	 *  * #Following JavaScriptMVC
	 *  * ##Twitter
	 *  * [![twitter][2]][1]
	 *  * [1]: http://twitter.com/javascriptmvc
	 *  * [2]: http://wiki.javascriptmvc.com/wiki/images/f/f7/Twitter.png
	 *  *
	 *  * Follow [http://twitter.com/javascriptmvc @javascriptmvc] on twitter for daily useful tips.
	 *  * ##Blog
	 *  * [![blog][2]][1]
	 *  * [1]: http://jupiterit.com/
	 *  * [2]: http://wiki.javascriptmvc.com/wiki/images/e/e5/Blog.png  
	 *  *
	 *  * Read [http://jupiterit.com/ JavaScriptMVC's Blog] for articles, techniques and ideas
	 *  * on maintainable JavaScript.
	 *  * ##Email List
	 *  * [![email list][2]][1]
	 *  * [1]: http://forum.javascriptmvc.com/
	 *  * [2]: http://wiki.javascriptmvc.com/wiki/images/8/84/Discuss.png  
	 *  
	 *  * Discuss ideas to make the framework better or problems you are having on  [http://forum.javascriptmvc.com/ JavaScriptMVC's Forum] 
	 * .*
	 *  *|
	 * @codeend
	 * 
	 * ###End Result:
	 * 
	 * @image jmvc/images/page_type_example.png 970
	 */
	DocumentJS.Type("page", {
		code: function() {

		},
	/*
	 * Possible scopes for @page.
	 */
		parent: /page/,
		useName: false,
		hasChildren: true
	})
})