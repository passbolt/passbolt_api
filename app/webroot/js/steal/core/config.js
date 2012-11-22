/**
 * @function st.getScriptOptions
 *
 * `steal.getScriptOptions` is used to determine various
 * options passed to the steal.js file:
 *
 * - should we load the production version of the 
 *   (if you use steal.production.js instead of steal.js)
 * - parts of the query string to determine `startFile`
 * - location of the `root url`
 */

st.getScriptOptions = function (script) {

	var options = {},
		parts, src, query, startFile, env;

	script = script || h.getStealScriptSrc();

	if (script) {

		// Split on question mark to get query
		parts = script.src.split("?");
		src = parts.shift();
		// // for IE7, where the script.src is always relative
		// if(!/\/\//.test(src)){
		// 	var dir = URI.page.dir();
		// 	src = URI(dir.join(src))+"";
		// }
		
		query = parts.join("?");

		// Split on comma to get startFile and env
		parts = query.split(",");

		if (src.indexOf("steal.production") > -1) {
			options.env = "production";
		}

		// Grab startFile
		startFile = parts[0];

		if (startFile) {
			if (startFile.indexOf(".js") == -1) {
				startFile += "/" + startFile.split("/").pop() + ".js";
			}
			options.startFile = startFile;
		}

		// Grab env
		env = parts[1];

		if (env) {
			options.env = env;
		}

		// Split on / to get rootUrl
		parts = src.split("/")
		parts.pop();
		if (parts[parts.length - 1] == "steal") {
			parts.pop();
		}
		var root = parts.join("/");
		options.root = root

	}

	return options;
};