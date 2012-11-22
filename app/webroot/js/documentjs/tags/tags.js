
steal("./alias",
"./author",
"./codeend",
"./codestart",
"./constructor",
"./demo",
"./description",
"./download",
"./hide",
"./iframe",
"./inherits",
"./page",
"./param",
"./parent",
"./plugin",
"./return",
"./scope",
"./tag",
"./test",
"./type",
"./release",
"./image",
function(alias, author, codeend, codestart, constructor, demo, description, download,
	hide, iframe, inherits, page, param, parent, plugin, ret, scope, tag, test, type,
	release, image) {
	return {
		alias: alias,
		author: author,
		codeend: codeend,
		codestart: codestart,
		constructor: constructor,
		demo: demo,
		description: description,
		download: download,
		hide: hide,
		iframe: iframe,
		inherits: inherits,
		page: page,
		param: param,
		parent: parent,
		plugin: plugin,
		release: release,
		"return": ret,
		scope: scope,
		tag: tag,
		test: test,
		type: type,
		image: image
	}
	
	/**
	 * @attribute DocumentJS.tags
	 * @parent DocumentJS
	 * A tag adds additional information to the comment being processed.
	 * For example, if you want the current comment to include the author,
	 * include an @@author tag.
	 * 
	 * ## Creating your own tag
	 * 
	 * To create a tag, you need to add to DocumentJS.tags an object with an add and an optional
	 * addMore method like:
	 * 
	 * @codestart
	 * DocumentJS.tags.mytag = {
	 *   add : function(line){ ... },
	 *   addMore : function(line, last){ ... }
	 * }
	 * @codeend 
	 */

})
