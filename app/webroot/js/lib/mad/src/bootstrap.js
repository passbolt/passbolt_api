import mad from "mad/mad";
import "mad/net/ajax";
import "mad/control/bus";
import "mad/util/lang/i18n";
import madConfig from "mad/config/config.json";

// Load the default mad config.
// See mad/config/config.json
mad.Config.load(madConfig);

// If an url is given to load a dictionnary of translations.
//var dictionnaryUrl = mad.Config.read("i18n.dictionnaryUrl");
//console.log(dictionnaryUrl);
//if (dictionnaryUrl) {
    //mad.net.Ajax.request({
    //    'url': url,
    //    'async': false,
    //    'dataType': 'json',
    //    'success': function (request, response, data) {
    //        // Load the dictionnary.
    //        mad.I18n.loadDico(data);
    //    },
    //    'error': function (request, response) {
    //        steal.dev.warn('Unable to load the client dictionnary');
    //    }
    //});
//}

/**
 * @inherits can.Construct
 * @parent Mad.core_api
 *
 * The Boostrap class is the application launcher. It takes care of initializing :
 *
 * * The application namespace ;
 * * The global variables ;
 * * The event bus ;
 * * The route handler ;
 * * The internationalization layer ;
 * * The application ;
 *
 *    ##Example
 *    The bootstrap use by the demo found in the documentation
 *
 * ```
 // Launch the application and its master pieces
 var boot = new passbolt.bootstrap.AppBootstrap({ 'config': [ 'app/config/config.json' ] });
 * ```
 *
 * ##Config Example
 *
 * ```
 {
     "app": {
     "url": "http://passbolt.local",
         "controllerElt": "#js_app_controller",
         "namespace": "passbolt",
         "ControllerClassName": "passbolt.controller.AppController"
 },
     "error": {
     "ErrorHandlerClassName": "passbolt.helper.ErrorHandler"
 },
     "event": {
     "eventBusControllerElt": "#js_bus_controller"
 },
     "i18n": {
     "lang": "EN-en"
 },
     "net": {
     "ResponseHandlerClassName": "passbolt.net.ResponseHandler"
 },
     "route": {
     "defaultRoute": {
         "extension": "passbolt",
             "controller": "passwordWorkspace",
             "action": "index"
     }
 }
  *	```
  *
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
var Boostrap = mad.Bootstrap = can.Construct.extend('mad.Bootstrap', /* @static */ {

    defaults: {
        // Callbacks.
        callbacks: {
            // The application is ready.
            ready: null
        }
    }

}, /**  @prototype */ {

    // constructor like
    init: function (options) {
        this.options = {};
        options = options || {};

        // Merge the default class options with the ones given in parameters.
        $.extend(true, this.options, mad.Bootstrap.defaults, options);

        // Check the application url.
        var appUrl = mad.Config.read('app.url');
        if (typeof appUrl == 'undefined') {
            throw mad.Exception.get(mad.error.MISSING_CONFIG, 'app.url');
        }
        // Set APP_URL as global variable.
        mad.setGlobal('APP_URL', appUrl);

        // Define Error Handler Class
        var ErrorHandlerClass = can.getObject(mad.Config.read('error.ErrorHandlerClassName'));
        // Has to be a mad.error.ErrorHandler
        if (!ErrorHandlerClass) {
            throw mad.Exception.get(mad.error.MISSING_CONFIG, 'error.ErrorHandlerClassName');
        }
        mad.Config.write('error.ErrorHandlerClass', ErrorHandlerClass);

        // Define Response Handler Class
        var ResponseHandlerClass = can.getObject(mad.Config.read('net.ResponseHandlerClassName'));
        // Has to be a mad.net.ResponseHandler
        if (!ResponseHandlerClass) {
            throw mad.Exception.get(mad.error.MISSING_CONFIG, 'net.ResponseHandlerClassName');
        }
        mad.Config.write('net.ResponseHandlerClass', ResponseHandlerClass);

        // Define App Controller Class
        var AppControllerClass = can.getObject(mad.Config.read('app.ControllerClassName'));
        // Has to be a mad.net.ResponseHandler
        if (!AppControllerClass) {
            throw mad.Exception.get(mad.error.MISSING_CONFIG, 'app.ControllerClassName');
        }
        mad.Config.write('app.AppControllerClass', AppControllerClass);

        // The app controller element has to be defined, and to be a reference to
        // an existing DOM element
        if (!$(mad.Config.read('app.controllerElt')).length) {
            throw mad.Exception.get(mad.error.MISSING_CONFIG, 'app.controllerElt');
        }

        // Reference the application namespace if it does not exist yet
        var ns = can.getObject(mad.Config.read('app.namespace'), window, true);

        // Load the required component
        var components = mad.Config.read('core.components');
        for (var i in components) {
            if (components[i] == 'Devel' && (mad.Config.read('app.debug') == null ||
                mad.Config.read('app.debug') == 0)) {
                continue;
            }
            this['init' + components[i]]();
        }
    },

    /**
     * Init application
     */
    initAppController: function () {
        var self = this;
        mad.bus.bind('app_ready', function () {
            if (self.options.callbacks.ready) {
                self.options.callbacks.ready();
            }
        });
        var AppControllerClass = can.getObject(mad.Config.read('app.ControllerClassName'));
        var app = new AppControllerClass($(mad.Config.read('app.controllerElt')));
        app.start();
    },

    /**
     * Initialize the Application Event Bus Controller.
     */
    initEventBus: function () {
        var elt = mad.helper.Html.create(
            $(mad.Config.read('app.controllerElt')),
            'before',
            '<div/>'
        );
        var eventBus = new mad.Bus(elt);
        mad.bus = eventBus;
    },

    /**
     * Initialize the Application Development tools
     */
    initDevel: function () {
        var elt = mad.helper.HtmlHelper.create(
            $(mad.Config.read('app.controllerElt')),
            'before',
            '<div/>'
        );
        var dev = new mad.devel.Devel(elt);
        mad.dev = dev;
    }
});

export default Boostrap;
