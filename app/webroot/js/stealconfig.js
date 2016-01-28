steal.config({
    map: {
        "jquery/jquery": "jquery",
        "underscore": "lib/underscore/underscore",
        "can": "lib/can",
		"urijs": "lib/urijs/src"
    },
    paths: {
        "passbolt": "app/passbolt.js",
        "jquery": "lib/jquery/dist/jquery.js",
        "mad": "lib/mad/src/mad.js",
        "mad/*": "lib/mad/src/*.js",
        "xregexp": "lib/xregexp/xregexp-all.js",
		"sha1": "lib/jsSHA/src/sha.js",
		"moment": "lib/moment/moment.js",
	    "moment-timezone": "lib/moment-timezone/builds/moment-timezone-with-data.js"
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
