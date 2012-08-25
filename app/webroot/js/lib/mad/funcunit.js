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
//		$.get('//'+MAD_ROOT+'/view/template/component/decorator/box.ejs', {}, function(){}, 'view');
		
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
	
//    "./test/controller/appController.js",
    "./test/controller/controller.js",
    "./test/controller/containerController.js",
//    "./test/controller/componentController.js",
//	"./test/controller/component/treeController.js",
    "./test/core/class.js",
    "./test/core/singleton.js",
    "./test/error/error.js",
	"./test/event/eventBus.js",
//	"./test/helper/component/boxDecorator.js",
//    "./test/helper/controller.js",
//    "./test/lang/i18n.js",
//    "./test/net/ajax.js",
	"./test/object/map.js",
//	"./route/extensionControllerActionDispatcher.js"
	"./test/route/routeListener.js",
    "./test/string/uuid.js"

);
