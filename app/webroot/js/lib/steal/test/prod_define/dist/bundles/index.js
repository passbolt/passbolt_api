/*[system-bundles-config]*/
System.bundles = {"bundles/page1-page2":["jqwerty","jqwertyui"],"bundles/components/page1":["components/page1"],"bundles/components/page2":["components/page2"]};
/*stealconfig.js*/
define('stealconfig.js', function(require, exports, module) {
(function () {
	// taking from HTML5 Shiv v3.6.2 | @afarkas @jdalton @jon_neal @rem | MIT/GPL2 Licensed
	var supportsUnknownElements = false;

	(function () {
		try {
			var a = document.createElement('a');
			a.innerHTML = '<xyz></xyz>';

			supportsUnknownElements = a.childNodes.length == 1 || (function () {
				// assign a false positive if unable to shiv
				(document.createElement)('a');
				var frag = document.createDocumentFragment();
				return (
					typeof frag.cloneNode == 'undefined' ||
						typeof frag.createDocumentFragment == 'undefined' ||
						typeof frag.createElement == 'undefined'
					);
			}());
		} catch (e) {
			// assign a false positive if detection fails => unable to shiv
			supportsUnknownElements = true;
		}
	}());


	System.config({
		transpiler: "babel",
		map: {
			"can/util/util": "can/util/jquery/jquery",
			"jquery/jquery": "jquery"
		},
		paths: {
			"jquery": "bower_components/jquery/dist/jquery.js",
			"jqueryui": "bower_components/jquery-ui/jquery-ui.js"
		},
		meta: {
      jqwerty: {
        exports: "jQwerty"
      }
		},
		//ext: {
		//	stache: "can/view/stache/system"
		//},
		bundle:[
			"components/page1",
			"components/page2"
		]
	});

	System.buildConfig = {
		map: {
			"can/util/util": "can/util/domless/domless"
		}
	};
})();


});
/*index*/
define('index', function(require, exports, module) {
"format cjs";

System.import('components/page1')
    .then(function() {
		if(typeof window !== "undefined" && window.QUnit) {
			QUnit.ok(true, "Loaded page 1");
			QUnit.equal(window.jqwerty.modName, "jqwerty", "jqwerty loaded");
			QUnit.equal(typeof window.jqwerty.ui, "function", "jqwertyui loaded");
			
			QUnit.start();
			removeMyself();
			return {};
		} else {
			console.log("Loaded page 1");
			console.assert(window.jqwerty.modName == "jqwerty");
		}
    })
    .catch(function(ex) {
		if(typeof window !== "undefined" && window.QUnit) {
			QUnit.ok(false, "Unable to load page 1");

			QUnit.start();
			removeMyself();
		} else {
			console.error("unable to load page 1");
			throw ex;
		}
    });


});
