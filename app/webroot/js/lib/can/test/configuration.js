LIBRARIES = {
	jquery : {
		name : 'jQuery',
		dist : 'can/util/jquery/jquery.1.8.2.js',
		libraryLoaded : function () {
			return window.jQuery;
		},
		steal : {
			map : {
				"*" : {
					"jquery/jquery.js" : "jquery",
					"can/util/util.js" : "can/util/jquery/jquery.js"
				}
			},
			paths : {
				"jquery" : "can/util/jquery/jquery.1.8.2.js"
			}
		}
	},
	yui : {
		name : 'YUI',
		dist : 'can/util/yui/yui-3.7.3.js',
		libraryLoaded : function () {
			return window.YUI;
		},
		steal : {
			map : {
				"*" : {
					"can/util/util.js" : "can/util/yui/yui.js"
				}
			}
		}
	},
	zepto : {
		name : 'Zepto',
		dist : 'can/util/zepto/zepto.1.0rc1.js',
		libraryLoaded : function () {
			return window.Zepto;
		},
		steal : {
			map : {
				"*" : {
					"can/util/util.js" : "can/util/zepto/zepto.js"
				}
			}
		}
	},
	mootools : {
		name : 'Mootools',
		dist : 'can/util/mootools/mootools-core-1.4.3.js',
		libraryLoaded : function () {
			return window.MooTools;
		},
		steal : {
			map : {
				"*" : {
					"can/util/util.js" : "can/util/mootools/mootools.js"
				}
			}
		}
	},
	dojo : {
		name : 'Dojo',
		dist : 'can/util/dojo/dojo-1.8.1.js',
		libraryLoaded : function () {
			return window.dojo;
		},
		steal : {
			map : {
				"*" : {
					"can/util/util.js" : "can/util/dojo/dojo.js"
				}
			}
		}
	}
}

if(typeof steal !== 'undefined') {
	steal(function() {
		return LIBRARIES;
	});
}
