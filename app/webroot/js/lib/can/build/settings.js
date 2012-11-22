steal(function () {
	return {
		"jquery" : {
			exclude : ["jquery", "jquery/jquery.js"],
			wrapInner : ['(function(window, $, undefined) {\n', '\n})(this, jQuery);']
		},
		"zepto" : {
			exclude : ["zepto", "zepto/zepto.js"],
			wrapInner : ['(function(window, $, undefined) {\n', '\n})(this, Zepto)']
		},
		"mootools" : {
			exclude : ["mootools", "mootools/mootools.js"]
		},
		"dojo" : {
			exclude : ["dojo", "dojo/dojo.js"]
			/* TODO probably needs to look somehow like this
			 wrapInner : [
			 '\ndefine("can/dojo", ["dojo/query", "dojo/NodeList-dom", "dojo/NodeList-traverse"], function(){\n',
			 '\nreturn can;\n});\n'
			 ]
			 */
		},
		"yui" : {
			exclude : ["yui", "yui/yui.js"]
			/* TODO probably needs to look somehow like this
			 wrapInner : [
			 '(function(can, window, undefined){\nYUI().add("can", function(Y) {\ncan.Y = Y;\n',
			 '}, "0.0.1", {\n' +
			 'requires: ["node", "io-base", "querystring", "event-focus", "array-extras"],' +
			 '\n optional: ["selector-css2", "selector-css3"]\n});\n})(can = {}, this );'
			 ]
			 */
		}
	}
});