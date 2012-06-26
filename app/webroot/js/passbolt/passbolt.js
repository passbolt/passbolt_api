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

MAD_ROOT = 'lib/mad';
steal(
    './passbolt.css'                                    // application CSS file
    , MAD_ROOT+'/mad.js'
    
//    , 'lb/core/controller/controller.js'                // the application's controller
//    
//    , 'lb/core/controller/eventBusController.js'        // the event bus controller
    , 'app/bootstrap/appBootstrap.js'                       // passbolt application bootstrap
    , 'app/controller/appController.js'                     // passbolt main application controller
)
.then(
    'jquery/plugin/jquery-ui-1.8.20.custom.min.js'      // load jquery ui lib
)
.then(function(){
        steal.options.logLevel = 0;
            
        $(document).ready(function(){

            //load the bootstrap of the application
            var boot = new passbolt.bootstrap.AppBootstrap({
                'appRootUrl' : 'http://passbolt.local'                                      // Application root url
                , 'lg' : 'en-EN'                                                            // The langue of the application
                , 'appNamespaceId' : 'passbolt'                                             // Application namespace
                , 'appControllerId' : 'passbolt_app_controller'                             // Application controller DOM node id
                , 'appControllerClass' : passbolt.controller.AppController         // Application controller class
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