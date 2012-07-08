MAD_ROOT = 'lib/mad';
APP_URL = 'http://passbolt.local';
APP_NS_ID = 'mad_test_appNs';
var APP_CTL_ID = 'mad_test_appCtlId';
var APP_EVENTBUS_ID = 'mad_test_eventBusId';

steal(
    'funcunit',
    'jquery/dom/fixture',
    MAD_ROOT+'/mad.js'
)
.then(

	function(){
		// The default testing module
		// It is just an example to show how it will work
		// Each test will be executed with the last module
		// In this module you can create you tests environment
		module("MadSquirrel", {
			// runs before each test
			setup: function(){
			},
			// runs after each test
			teardown: function(){
			}
		});	
	},
	
//    "./controller/appController.js",
//    "./controller/controller.js",
//    "./controller/containerController.js",
	"./controller/component/treeController.js"
//    "./core/class.js",
//    "./core/singleton.js",
//    "./error/error.js",
//	"./event/eventBus.js",
//	"./helper/component/boxDecorator.js",
//    "./helper/controller.js",
//    "./lang/i18n.js",
//    "./net/ajax.js",
//	"./object/map.js",
//	"./route/extensionControllerActionDispatcher.js",
//	"./route/routeListener.js",	
//    "./string/uuid.js"

);
