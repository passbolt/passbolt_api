steal( 
    'jquery/dom/route'
    , 'jquery/plugin/jquery.uuid.js'
    , 'lb/core/model/bootstrapInterface.js'
    , 'lb/core/model/ajaxWrapper.js'
    , 'lb/core/controller/appController.js'
    , 'lb/core/model/dispatcherInterface.js'
    , 'lb/core/controller/eventBusController.js'
    , 'lb/core/error/includeAll.js'
    
    , 'passbolt/activity/model/bootstrap.js'                // Extension bootstrap, should be enabled by the php script
)
.then( 
    function($){
        
        /*
        * @class lb.core.model.AppBootstrap
        * @parent index
        * @constructor
        * Creates a Application Bootstrap
        * @param {Array} options Array of options
        * @param {String} options.appControllerId Id of the application controller. A DOM element with this ID must
        * exist on your page. Default : app-controller
        * @param {Array} options.dispatchOptions Array of options for the dispatcher. See the Class lb.core.model.Dispatcher
        * @param {Array} defaultRoute The default route used by the dispatcher
        * @param {String} defaultRoute.module The default module
        * @param {String} defaultRoute.controller The default controller
        * @param {String} defaultRoute.action The default action
        */
        lb.core.model.BootstrapInterface.extend('lb.core.model.AppBootstrap', 
        
        /*
        * @static
        */
        {
            'defaults' : {
                'appRootUrl'        : ''
                , 'lang'            : 'en-EN'
                , 'appControllerId' : 'app-controller'
                , 'appNamespaceId'  : 'app'
                , 'appControllerClass' : lb.core.controller.AppController
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
                this.options = $.extend(true, {},  lb.core.model.AppBootstrap.defaults, options);
                
                // check compulsory options (an option compulsory lol)
                if($.trim(this.options.appRootUrl) === ''){
                    throw new lb.core.error.MissingOption('appRootUrl', 'lb.core.model.AppBootstrap');
                }
                
                // find the controller with the given appControllerId passed by args
                this.$appController = $('#'+this.options.appControllerId);
                // if the DOM does not contain a reference to an element with the given appControllerId
                // throw an Error
                if(!this.$appController.length){
                    throw new Error('AppBootstrap error : Your template must contain a node element with the id ('+this.options.appControllerId+')');
                }
                
                // 
                // BEGINING OF THE APPLICATION BOOTSTRAP PROCESS
                // 
                
                // @todo load the authentication service
                
                // Initialize app globals
                this.initGlobals();
                
                // Initialize app constante
                this.initConstants();
                
                // Initialize internationalization
                this.initInternationalization();
                
                // Initialize the event bus controller
                this.initEventBus();
                
                // Initialize the route listener of the application. It will be in charge to listen any changes
                // on the hash and use the function dispatch to perform the desired action.
                this.initRouteListener();
                
                // Initialize the application
                this.initApplication();
                
                // Initialize modules
                this.initModules();
                
                // Dispatch the route to the convenient action
                this.dispatch();
                
                // Application is ready
                this.ready();
                
                // 
                // END OF THE APPLICATION BOOTSTRAP PROCESS
                // 
            },
            
            
            /**
             * Init application globals
             * @return {void}
             */
            'initGlobals' : function()
            {
                lb.eventBus = null;
                lb.app = null;
                lb.ajaxWrapper = lb.core.model.AjaxWrapper.getInstance();
            },
            
            /**
             * Init application constants
             * @return {void}
             */
            'initConstants' : function()
            {
                lb.APP_ROOT_URL = this.options.appRootUrl;
                lb.LG = this.options.lg;
                lb.APP_NAMESPACE_ID = this.options.appNamespaceId;
                lb.APP_CONTROLLER_ID = this.options.appControllerId;
                lb.EVENTBUS_CONTROLLER_ID = this.options.eventBusControllerId;
            },
            
            /** 
             * Initialize the internationalization service
             * @return {void}
             */
            'initInternationalization' : function()
            {
                // Load the javascript dictionnary
                lb.ajaxWrapper.request({
                    'url':          lb.APP_ROOT_URL+'/lg/jsDictionnary',
                    'async':        false,
                    'dataType':     'json',
                    'success':      function(DATA){
                        __.loadDico(DATA);
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
                lb.app = new appControllerClass(this.$appController);
            },
            
            /**
             * Init application
             * @return {void}
             * @todo make this operation automatic
             */
            'initModules': function()
            {
                new passbolt.activity.model.Bootstrap();
            },
            
            /**
             * Initialize the event bus controller of the application. It will be in charge to centralize
             * all events which occur
             * @return {void}
             */
            'initEventBus': function()
            {
                // initialize the event bus of the application
                var pluginNameController = lb.core.controller.EventBusController._fullName;
                // add the dom element which will be behind the controller
                this.$appController.before('<div id="'+lb.EVENTBUS_CONTROLLER_ID+'"></div>');
                // instantiate the event bus controller
                lb.eventBus = $('#'+lb.EVENTBUS_CONTROLLER_ID)[pluginNameController]();
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
             * @event app_ready
             * @return {void}
             */
            'ready': function()
            {
                this.$appController.controller().getEventBus().trigger('lb_app_ready');
            }
        });
    }
);
