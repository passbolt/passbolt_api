APP_URL = 'http://passbolt.local';
APP_NS_ID = 'mad_test_appNs';
var APP_CTL_ID = 'mad_test_appCtlId';
var APP_EVENTBUS_ID = 'mad_test_eventBusId';

steal(
	'mad',
	'funcunit/qunit/qunit.css',
	'funcunit',
	'mad/test/testing.js'
).then(function(){
	// var api = function (url, method, data) {
		// var deferred = Q.defer();
		// request({ 
			// method: method, 
			// uri: ["https://", username, ":", accessKey, "@saucelabs.com/rest", url].join(""), 
			// headers: {'Content-Type': 'application/json'}, 
			// body: JSON.stringify(data)
		// }, function (error, response, body) {
			// deferred.resolve(response.body);
		// });
		// return deferred.promise;
	// };
	// QUnit.done = function (test_results) {
		// console.dir(test_results);
		// // global_test_results = test_results;
		// // return api(["/v1/", username, "/jobs/", browser.sessionID].join(""), "PUT", data);
	// };
}).then(
	// "./test/controller/appController.js",
	// "./test/controller/controller.js",
// //	//"./test/controller/containerController.js"
	// "./test/controller/componentController.js",
	// "./test/controller/component/treeController.js",
	// "./test/controller/component/dynamicTreeController.js",
	// "./test/controller/component/dropDownController.js",
	// "./test/controller/component/menuController.js",
	// "./test/controller/component/gridController.js",
	// "./test/core/class.js",
	// "./test/core/singleton.js",
	// "./test/error/error.js",
	// // "./test/event/eventBus.js",
// //	//"./test/helper/component/boxDecorator.js",
	// "./test/helper/component.js",
	// "./test/helper/html.js",
	// "./test/form/formController.js",
//	//"./test/helper/controller.js",
	"./test/lang/i18n.js"
	// "./test/model/model.js",
	// "./test/model/serializer/cakeSerializer.js",
	// "./test/model/validationRules.js",
	// "./test/net/ajax.js",
	// "./test/object/map.js",
	// //"./route/extensionControllerActionDispatcher.js"
	// //"./test/route/routeListener.js",
	// "./test/string/uuid.js"
);