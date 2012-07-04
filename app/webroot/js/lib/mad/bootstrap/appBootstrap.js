steal( 
    'jquery/dom/route'
    , MAD_ROOT+'/bootstrap/bootstrapInterface.js'    
    , 'plugin/activity/bootstrap/bootstrap.js'                  // Extension bootstrap, should be enabled by the php script
)
.then( 
    function($){
        
        /*
        * @class mad.bootstrap.AppBootstrap
        * BLABLA sur la classe
        * 
        * @parent index
        * @constructor
        * Creates a Application Bootstrap
        * @param {Array} options Array of options
        * @param {String} options.appControllerId Id of the application controller. A DOM element with this ID must
        * exist on your page. Default : app-controller
        * @param {Array} options.dispatchOptions Array of options for the dispatcher. See the Class mad.bootstrap.DispatcherInterface
        * @param {Array} defaultRoute The default route used by the dispatcher
        * @param {String} defaultRoute.extension The default extension
        * @param {String} defaultRoute.controller The default controller
        * @param {String} defaultRoute.action The default action
        * @return {mad.bootstrap.AppBootstrap}
        */
        mad.bootstrap.BootstrapInterface.extend('mad.bootstrap.AppBootstrap', 
        
        /*
        * @static
        */
        {
            'defaults' : {
                'appRootUrl'        : ''
                , 'lang'            : 'en-EN'
                , 'appControllerId' : 'app-controller'
                , 'appNamespaceId'  : 'app'
                , 'appControllerClass' : mad.controller.AppController
                , 'dispatchOptions' : { }
                , 'defaultRoute' : {
                    'extension'        : ''
                    , 'controller'  : ''
                    , 'action'      : ''
                }
            }
        }, 
        
        /*
        * @prototype
        */
        {   
            'init': function(options)
            {
                // the last route used
                this.lastRoute = null;
                // the current route
                this.currentRoute = null;
                // the event bus controller
                this.bus = null;
                // array of options passed by args
                this.options = {};
                
                // extend default options with args options
                this.options = $.extend(true, {},  mad.bootstrap.AppBootstrap.defaults, options);
                
                // check compulsory options (an option compulsory lol)
                if($.trim(this.options.appRootUrl) === ''){
                    throw new mad.error.MissingOption('appRootUrl', 'mad.bootstrap.AppBootstrap');
                }
                
                // Reference the application namespace
                mad.controller.AppController.setNs(this.options.appNamespaceId);
                
                // find the controller with the given appControllerId passed by args
                var $appController = mad.setGlobal('$appController', $('#'+this.options.appControllerId));
                // if the DOM does not contain a reference to an element with the given appControllerId
                // throw an Error
                if(!$appController.length){
                    throw new Error('AppBootstrap error : Your template must contain a node element with the id ('+this.options.appControllerId+')');
                }
                
                // 
                // BEGINING OF THE APPLICATION BOOTSTRAP PROCESS
                // 
                
                // @todo load the authentication service
                
                // Initialize app globals variables
                this.initConstants();
                
                // Initialize app globals objects
                this.initGlobals();
                
                // Initialize internationalization
                this.initInternationalization();
                
                // Initialize the event bus controller
                this.initEventBus();
                
                // Initialize the route listener of the application. It will be in charge to listen any changes
                // on the hash and use the function dispatch to perform the desired action.
                this.initRouteListener();
                
                // Initialize the application
                this.initApplication();
                
                // Initialize extensions
                this.initExtensions();
                
                // Dispatch the route to the convenient action
                this.dispatch();
                
                // Application is ready
                this.ready();
                
                // 
                // END OF THE APPLICATION BOOTSTRAP PROCESS
                // 
            },
            
            /**
             * Init application constants
             * @return {void}
             */
            'initConstants' : function()
            {
                // init globals
                mad.setGlobal('APP_ROOT_URL',               this.options.appRootUrl);           // Reference the application url
                mad.setGlobal('LG',                         this.options.lg);                   // Reference the application language
                mad.setGlobal('APP_NS_ID',                  this.options.appNamespaceId);       // The application NS Id
                mad.setGlobal('APP_NS',                     window[mad.controller.AppController.getGlobal('APP_NS_ID')]);   // The application NS
                mad.setGlobal('APP_CONTROLLER_ID',          this.options.appControllerId);      // The application controller Id
                mad.setGlobal('EVENTBUS_CONTROLLER_ID',     this.options.eventBusControllerId); // The event bus controller id
                mad.setGlobal('APP_CONTROLLER_CLASS',       this.options.appControllerClass);   // The application controller class
            },
            
            
            /**
             * Init application globals
             * @return {void}
             */
            'initGlobals' : function()
            {
                mad.setGlobal('eventBus',                  null);
                mad.setGlobal('app',                       null);
            },
            
            /** 
             * Initialize the internationalization service
             * @return {void}
             */
            'initInternationalization' : function()
            {
                // Load the javascript dictionnary
                mad.net.Ajax.singleton().request({
                    'url':          mad.getGlobal('APP_ROOT_URL')+'/lg/jsDictionnary',
                    'async':        false,
                    'dataType':     'json',
                    'success':      function(DATA){
                        mad.lang.I18n.singleton().loadDico(DATA);
                    }
                });
            },
            
            /**
             * Init application
             * @return {void}
             */
            'initApplication': function()
            {
                var appControllerClass = this.options.appControllerClass;
                mad.setGlobal('app', appControllerClass.singleton(mad.getGlobal('$appController')));
            },
            
            /**
             * Init application's extensions
             * @return {void}
             * @todo make this operation automatic
             */
            'initExtensions': function()
            {
                new passbolt.activity.bootstrap.Bootstrap();
            },
            
            /**
             * Initialize the event bus controller of the application. It will be in charge to centralize
             * all events which occur
             * @return {void}
             * @todo change the use to mad.eventBus in mad.$eventBus (because it is a jQuery element)
             */
            'initEventBus': function()
            {
                // initialize the event bus of the application
                var pluginNameController = mad.event.EventBus._fullName;
                // add the dom element which will be behind the controller
                mad.getGlobal('$appController').before('<div id="'+mad.getGlobal('EVENTBUS_CONTROLLER_ID')+'"></div>');
                // instantiate the event bus controller
                var eventBus = new mad.event.EventBus('#'+mad.getGlobal('EVENTBUS_CONTROLLER_ID'));
                mad.setGlobal('eventBus', eventBus.element);
                // Make an alias with the eventBus
                mad.eventBus = mad.getGlobal('eventBus');
            },
            
            /**
             * Initialize the route listener of the application. It will be in charge to listen any changes
             * on the hash and use the function dispatch to perform the desired action.
             * @return {void}
             */
            'initRouteListener' : function(routes)
            {
                var self = this;
                mad.route.RouteListener.singleton();
                mad.eventBus.bind(mad.APP_NS_ID+'_route_change', function(){
                    self.dispatch();
                });
            },
            
            /**
             * Dispatch to the right action following the hash url
             * @use core.controller::getDispatcher()
             * @return {void}
             */
            'dispatch' : function()
            {               
                var hash = new String(location.hash);
                // extract the hash parameters
                // a hash has been defined
                if(hash != '' && hash != '#' && hash != '#!'){
                    hash = hash.substr(0, 2) == '#!' ? hash.substr(2) : hash;
                    this.currentRoute = $.route.deparam(hash);
                }
                //use de default option hash
                else{
                    this.currentRoute = this.options.defaultRoute;
                }
                this.lastRoute = this.currentRoute;
                
                    
                // check all required parameters are here
                if(typeof this.currentRoute.extension == 'undefined'){
                    throw new Error('Bootstrap error : the url is not valid, extension missing');
                }
                else if(typeof this.currentRoute.controller == 'undefined'){
                    throw new Error('Bootstrap error : the url is not valid, controller missing');
                }
                else if(typeof this.currentRoute.action == 'undefined'){
                    throw new Error('Bootstrap error : the url is not valid, action missing');
                }
                
                // get the target controller
                // the controller name
                // @todo application name parameter
                var controllerName = this.currentRoute.controller.charAt(0).toUpperCase()+this.currentRoute.controller.slice(1)+'Controller';
                steal.dev.log('dispatch to extension:'+this.currentRoute.extension+' controller:'+controllerName+' action:'+this.currentRoute.action);
                var controllerClass = passbolt[this.currentRoute.extension].controller[controllerName];
                
                // dispatch to the convenient action
                this.options.dispatchOptions.ControllerClass = controllerClass;
                controllerClass.getDispatcher().dispatch(this.currentRoute, this.options.dispatchOptions);
            },
            
            /**
             * Execute this function at the end of the bootstrap process.
             * You can override this function, this one release an event app_Ready
             * @event {APP_NS_ID'}_app_ready
             * @return {void}
             */
            'ready': function()
            {
                mad.eventBus.trigger(mad.getGlobal('APP_NS_ID')+'_app_ready');
            }
        });
    }
);
