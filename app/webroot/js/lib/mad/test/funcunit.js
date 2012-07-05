MAD_ROOT = 'lib/mad';
APP_URL = 'http://passbolt.local';
var APP_NS_ID = 'mad_test_appNs';
var APP_CTL_ID = 'mad_test_appCtlId';
var APP_EVENTBUS_ID = 'mad_test_eventBusId';

steal(
    'funcunit',
    'jquery/dom/fixture',
    MAD_ROOT+'/mad.js'
)
.then(

	// Tools
//	"./object/object.js"

    // Test the function setNs, this function is a stone reference for the whole program
    // and it has to be called before the other unit test
    "./controller/appController.js",
    
    // Initialize the app namespace for the left unit tests 
    function(){
        test('Initialize the app namespace', function(){
            mad.controller.AppController.setNs(APP_NS_ID);  
        });
    },
    
    "./core/class.js",
    "./controller/controller.js",
    "./controller/containerController.js",
    "./core/singleton.js",
    "./error/error.js",
    "./helper/controller.js",
    "./lang/i18n.js",
    "./net/ajax.js",
    "./string/uuid.js",
    
    // Init an application controller, this one will be used by the unit tests which are following
    function(){
        test('Initialize an application controller', function(){
            $app = $('<div id="'+APP_CTL_ID+'"/>').appendTo('body');
            mad.controller.AppController.singleton($app);
        });
    },
    
    // Test the event bus controller, this object is a stone reference for the whole program
    "./event/eventBus.js",
    // Init the application event bus controller, this one will be used by the unit tests which are following
    function(){
        test('Initialize the application event bus controller', function(){
            var $eventBus = $('<div id="'+APP_EVENTBUS_ID+'" />').appendTo(mad.app.element);
            var eventBus = new mad.event.EventBus($eventBus);
            mad.setGlobal('eventBus', eventBus.element);
            mad.eventBus = mad.getGlobal('eventBus');
        });
    },
    
    "./route/routeListener.js",
    "./route/extensionControllerActionDispatcher.js",
    
    // Test application components
    "./helper/component/boxDecorator.js",
    
//    // Deploy an empty application environnement to test our components
//    // @deprecated The environnement is now launched in each test which needs it
//    function(){
//        test('Deploy popup test environnement', function(){
//			stop();
//            S.open('./testEnv.html', function(data1, d2){
//                // store the env windows in a global var for the following unit tests
//				testEnv = S.win;
//				console.log(S.win);
//				start();                
//            });
//        });
//    },
    
    "./controller/component/treeController.js"
);
