steal.config({
    //root: 'js/',
    map: {
        "jquery/jquery": "jquery",
        "underscore": "js/lib/underscore/underscore",
        "can": "js/lib/can",
        "test": "test"
    },
    paths: {
        "jquery": "js/lib/jquery/dist/jquery.js",
        "mad": "src/mad.js",
        "mad/*": "src/*.js",
        "steal-mocha": "node_modules/steal-mocha/steal-mocha.js",
        "steal-mocha/*": "node_modules/steal-mocha/*.js",
        "mocha": "node_modules/mocha/mocha.js",
        "mocha/mocha.css": "node_modules/mocha/mocha.css",
        "chai": "node_modules/chai/chai.js",
        "chai-jquery": "node_modules/chai-jquery/chai-jquery.js",
        "xregexp": "js/lib/xregexp/xregexp-all.js"
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
            "can/util/util": "js/lib/can/util/domless/domless"
        }
    }
});
