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
    , 'lb/core/controller/eventBusController.js'        // the event bus controller
    , 'passbolt/password/model/appBootstrap.js'         // passbolt application bootstrap
    , 'passbolt/password/controller/appController.js'   // passbolt main application controller
)
.then(
    'jquery/plugin/jquery-ui-1.8.20.custom.min.js'      // load jquery ui lib
)
.then(function(){

        steal.options.logLevel = 0;
            
        $(document).ready(function(){

            //load the bootstrap of the application
            var boot = new passbolt.password.model.AppBootstrap({
                'appNamespaceId' : 'passbolt'                                               // Application namespace
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