steal( 
    'jquery/dom/route'
    , MAD_ROOT+'/bootstrap/bootstrapInterface.js'
    , MAD_ROOT+'/route/dispatcherInterface.js'
    
    , 'plugin/activity/bootstrap/bootstrap.js'                  // Extension bootstrap, should be enabled by the php script
)
.then( 
    function($){
        
        /*
        * @class mad.bootstrap.AppBootstrap
        * @parent index
        * @constructor
        * Creates a Application Bootstrap
        * @param {Array} options Array of options
        * @param {String} options.appControllerId Id of the application controller. A DOM element with this ID must
        * exist on your page. Default : app-controller
        * @param {Array} options.dispatchOptions Array of options for the dispatcher. See the Class mad.bootstrap.DispatcherInterface
        * @param {Array} defaultRoute The default route used by the dispatcher
        * @param {String} defaultRoute.module The default module
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
                    'module'        : ''
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
                mad.controller.Controller.APP_NAMESPACE_ID = this.options.appNamespaceId;
                // Make an alias
                mad.appNamespaceId = mad.controller.Controller.APP_NAMESPACE_ID;
                
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
                // init the application namespace
                if(typeof window[this.options.appNamespaceId].APP_NAMESPACE_ID != 'undefined'){
                    throw new Error('The application namespace ('+this.options.appNamespaceId+') is yet existing.');
                }
                
                // make alias with the functionsx set and get globals
                window[this.options.appNamespaceId].getGlobal = mad.controller.AppController.getGlobal;
                window[this.options.appNamespaceId].setGlobal = mad.controller.AppController.setGlobal;
                
                // init globals
                mad.setGlobal('APP_ROOT_URL',              this.options.appRootUrl);
                mad.setGlobal('LG',                        this.options.lg);
                mad.setGlobal('APP_NAMESPACE_ID',          this.options.appNamespaceId);
                mad.setGlobal('APP_CONTROLLER_ID',         this.options.appControllerId);
                mad.setGlobal('EVENTBUS_CONTROLLER_ID',    this.options.eventBusControllerId);
                mad.setGlobal('APP_CONTROLLER_CLASS',      this.options.appControllerClass);
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
                //make an alias with this global variable
                mad.app = mad.getGlobal('app');
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
             */
            'initEventBus': function()
            {
                // initialize the event bus of the application
                var pluginNameController = mad.event.EventBus._fullName;
                // add the dom element which will be behind the controller
                mad.getGlobal('$appController').before('<div id="'+mad.getGlobal('EVENTBUS_CONTROLLER_ID')+'"></div>');
                // instantiate the event bus controller
                mad.setGlobal('eventBus', $('#'+mad.getGlobal('EVENTBUS_CONTROLLER_ID'))[pluginNameController]());
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
                
                // load the routes
                $.route(":module/:controller/:action/:p1/:p2/:p3/:p4/:p5");
                $.route(":module/:controller/:action/:p1/:p2/:p3/:p4");
                $.route(":module/:controller/:action/:p1/:p2/:p3");
                $.route(":module/:controller/:action/:p1/:p2");
                $.route(":module/:controller/:action/:p1");
                $.route(":module/:controller/:action");
                $.route(":module/:controller");        
                $.route(":module");
                $.route("");
                $.route.ready();
                
                // listen the special haschange event, disptatch when a new route is comming
                // @note : Using the $.route.bind('change', function(){ ... }) is maybe the proper method, but it seems impossible to listen the whole change (module+controler+action)
                $(window).bind('hashchange', function(){
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
                if(typeof this.currentRoute.module == 'undefined'){
                    throw new Error('Bootstrap error : the url is not valid, module missing');
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
                steal.dev.log('dispatch to module:'+this.currentRoute.module+' controller:'+controllerName+' action:'+this.currentRoute.action);
                var controllerClass = passbolt[this.currentRoute.module].controller[controllerName];
                
                // dispatch to the convenient action
                this.options.dispatchOptions.ControllerClass = controllerClass;
                controllerClass.getDispatcher().dispatch(this.currentRoute, this.options.dispatchOptions);
            },
            
            /**
             * Execute this function at the end of the bootstrap process.
             * You can override this function, this one release an event app_Ready
             * @event {APP_NAMESPACE_ID'}_app_ready
             * @return {void}
             */
            'ready': function()
            {
                mad.eventBus.trigger(mad.getGlobal('APP_NAMESPACE_ID')+'_app_ready');
            }
        });
    }
);
