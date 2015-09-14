// config.js
System.config({
	baseUrl: "/js/app",
	paths: {
		"passbolt": "/js/app/passbolt.js",
		"mad": "/js/lib/mad/src/mad.js",
		"mad/*": "/js/lib/mad/src/*.js",
		"app/*": "/js/app/*.js",
		"can/can": "/js/lib/can/can.js",
		"can/*":"/js/lib/can/*.js",
		"jquery": "/js/lib/jquery/dist/jquery.js",
		"jquery/jquery": "/js/lib/jquery/dist/jquery.js",
		"jquery/*": "/js/lib/jquery/dist/*.js",
		"xregexp": "/js/lib/xregexp/src/xregexp.js",
		"underscore": "/js/lib/underscore/underscore.js",
		"mootools/mootools.js" : "can/lib/mootools-core-1.4.3.js",
		"dojo/dojo.js" : "can/util/dojo/dojo-1.8.1.js",
		"yui/yui.js" : "can/lib/yui-3.7.3.js",
		"zepto/zepto.js" : "can/lib/zepto.1.0rc1.js",
	},
	"ext": {
		"ejs": "can/view/ejs/system"
	}
});