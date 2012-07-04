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

APP_URL = 'http://passbolt.local';
MAD_ROOT = 'lib/mad';
steal(
    './passbolt.css'                                        // application CSS file
    
    , MAD_ROOT+'/mad.js'                                    // the mad framework
    
    , 'app/bootstrap/appBootstrap.js'                       // passbolt application bootstrap
    , 'app/controller/appController.js'                     // passbolt main application controller
)
.then(
    'jquery/plugin/jquery-ui-1.8.20.custom.min.js'          // load jquery ui lib
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
                , 'appControllerClass' : passbolt.controller.AppController                  // Application controller class
                , 'eventBusControllerId' : 'passbolt_event_bus_controller'                  // Event bus controller DOM node id
                , 'dispatchOptions' : {                                                     // Dispatcher options (not used here, but in case of page to page application, the DOM node id of the page controller)
                    'pageControllerId'  : 'passbolt-page-controller'
                }
                , 'defaultRoute' : {                                                        // The default route
                    'extension' : 'password'
                    , 'controller' : 'passwordWorkspace'
                    , 'action' : 'index'
                }
            });
            
        });

    }
);