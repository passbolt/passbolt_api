MAD_ROOT = 'lib/mad';
APP_URL = 'http://passbolt.local';
var APP_NS_ID = 'unit_test_app_ns';

steal(
    'funcunit',
    'jquery/dom/fixture',
    MAD_ROOT+'/mad.js'
)
.then(
    // We want to test the function setNs, this function is a stone reference for
    // the whole programm
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
    function(){
        test('Initialize an application', function(){
            $app = $('body').append('<div id="mad-test-app_controller"/>');
            mad.controller.AppController.singleton($app);  
        });
    },
    "./helper/component/boxDecorator.js"
);