//// config.js
//System.config({
//    baseUrl: "/js/app",
//    paths: {
//        "passbolt": "/js/app/passbolt.js",
//        "mad": "/js/lib/mad/src/mad.js",
//        "mad/*": "/js/lib/mad/src/*.js",
//        "app/*": "/js/app/*.js",
//        "can/can": "/js/lib/can/can.js",
//        "can/*":"/js/lib/can/*.js",
//        "jquery": "/js/lib/jquery/dist/jquery.js",
//        "jquery/jquery": "/js/lib/jquery/dist/jquery.js",
//        "jquery/*": "/js/lib/jquery/dist/*.js",
//        "xregexp": "/js/lib/xregexp/src/xregexp.js",
//        "underscore": "/js/lib/underscore/underscore.js",
//        "mootools/mootools.js" : "can/lib/mootools-core-1.4.3.js",
//        "dojo/dojo.js" : "can/util/dojo/dojo-1.8.1.js",
//        "yui/yui.js" : "can/lib/yui-3.7.3.js",
//        "zepto/zepto.js" : "can/lib/zepto.1.0rc1.js",
//    },
//    "ext": {
//        "ejs": "can/view/ejs/system"
//    }
//});

steal.config({
    map: {
        "jquery/jquery": "jquery",
        "underscore": "lib/underscore/underscore",
        "can": "lib/can"
    },
    paths: {
        "passbolt": "app/passbolt.js",
        "jquery": "lib/jquery/dist/jquery.js",
        "mad": "lib/mad/src/mad.js",
        "mad/*": "lib/mad/src/*.js",
        "xregexp": "lib/xregexp/xregexp-all.js"
    },
    "meta": {
        "mocha": {
            "format": "global",
            "exports": "mocha",
            "deps": [
                "steal-mocha/add-dom"
            ]
        }
    },
    "ext": {
        "ejs": "can/view/ejs/system"
    }
});
System.config({
    buildConfig: {
        map: {
            "can/util/util": "lib/can/util/domless/domless"
        }
    }
});
