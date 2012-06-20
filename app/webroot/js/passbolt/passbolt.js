/*
 * @page index Passbolt
 * @tag home
 *
 * ###Passbolt
 *  
 * Our Passbolt
 *  
 * * passbolt.passbolt.controller.PasswordWorkspaceController
 */

steal(
    './passbolt.css'                                    // application CSS file
    
    , 'lib/i18n/i18n.js'
    , 'lb/core/controller/class.js'                     // the application's class
    , 'lb/core/controller/controller.js'                // the application's controller
    
    , 'lb/core/controller/eventBusController.js'        // the event bus controller
    , 'passbolt/password/model/appBootstrap.js'         // passbolt application bootstrap
    , 'passbolt/password/controller/appController.js'   // passbolt main application controller
)
.then(
    'jquery/plugin/jquery-ui-1.8.20.custom.min.js'      // load jquery ui lib
)
.then(function(){

        __("%stest de traduction avec variables %s et ouais gros %s", 'variable1', 'variable2', 'variable3');
        console.log(__("%stest de traduction avec variables %s et ouais gros %s", 'variable1', 'variable2', 'variable3'));

        steal.options.logLevel = 1;
            
        $(document).ready(function(){

            //load the bootstrap of the application
            var boot = new passbolt.password.model.AppBootstrap({
                'appRootUrl' : 'http://passbolt.local'                                      // Application root url
                , 'lg' : 'En-en'                                                            // The langue of the application
                , 'appNamespaceId' : 'passbolt'                                             // Application namespace
                , 'appControllerId' : 'passbolt_app_controller'                             // Application controller DOM node id
                , 'appControllerClass' : passbolt.password.controller.AppController         // Application controller class
                , 'eventBusControllerId' : 'passbolt_event_bus_controller'                  // Event bus controller DOM node id
                , 'dispatchOptions' : {                                                     // Dispatcher options (not used here, but in case of page to page application, the DOM node id of the page controller)
                    'pageControllerId'  : 'passbolt-page-controller'
                }
                , 'defaultRoute' : {                                                        // The default route
                    'module' : 'password'
                    , 'controller' : 'passwordWorkspace'
                    , 'action' : 'index'
                }
            });
            
        });

    }
);