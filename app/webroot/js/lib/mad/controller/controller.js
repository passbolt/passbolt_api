steal(
	'jquery/controller',
	'mad/route/extensionControllerActionDispatcher.js'
).then(function () {

	/*
	 * @class mad.controller.Controller
	 * @inherits jQuery.Controller
	 * @parent mad.core
	 * 
	 * The core class Controller is an extension of the JavascriptMVC Controller. This
	 * class allow us to easily hook common behavior, such as :
	 * 
	 * * referencing automatically newly created controllers with the [mad.controller.AppController|Application Controller]
	 * * set automatically an id to the DOM element if no id has been provided
	 * 
	 * @constructor
	 * Creates a new controller
	 * <br/> 
	 * References it to the application controller.
	 * @return {mad.controller.Controller}
	 */
	$.Controller('mad.controller.Controller', /** @static */ {
		/**
		 * Get the controller dispatcher. The Dispatcher explain how the routes have to
		 * be dispatch for this controller.
		 * 
		 * @return {mad.route.Dispatcher} By default return the common extension -> controller -> action
		 * dispatcher.
		 */
		'getDispatcher': function () {
			return mad.route.ExtensionControllerActionDispatcher;
		},

		/**
		 * Get the parent class.
		 * @return {mad.controller.Controller}
		 */
		'getParentClass': function () {
			// @todo __proto__ is not compatible with all browsers
			return this.prototype.__proto__.constructor;
		},

		/**
		 * Defaults attribute of the controller.
		 */
		'defaults': { }

	}, /** @prototype */ {

		// Class constructor
		'init': function (el, options) {
			// the el should exist
			if(!$(el).length) {
				throw new mad.error.Exception('The parameter "el" (' + $(el).selector + ') should refer to an existing DOM node.');
			}

			// The id is not given in the options.
			if (typeof options.id == 'undefined' || options.id == null || options.id == '') {
				// The id is maybe set directly on the templates.
				var elId = this.element.attr('id');
				if (typeof elId == 'undefined' || elId == '') {
					options.id = uuid();
					this.element.attr('id', options.id);
				} else {
					options.id = elId;
				}
			}
			// The id is given in the options.
			else {
				// The id is maybe set directly on the templates.
				var elId = this.element.attr('id');
				if (elId != '') {
					//console.warn('Controller id defined in options & templates for options.id = ' + options.id);
				}
				else {
					options.id = elId;
				}
			}

			// set the options
			this.options = $.extend(true, {}, this.options, options);
		},

		/**
		 * Destroy the controller
		 * @return {void}
		 */
		'destroy': function () {
			this._super();
		},

		/**
		 * Get Child controllers
		 * @todo or @deprecated
		 */
		'getComponent': function (id) {
			return mad.app.getComponent(id);
		},

		/**
		 * Get id of the controller
		 * @return {String} Id of the component
		 * @todo oula la the function should get id from the associated component controller model
		 */
		'getId': function () {
			return this.options.id;
		},

		/**
		 * Destroy the controller
		 * @return {void}
		 */
		'remove': function () {
			this.element.remove();
		},
		
		/**
		 * Get controller alias
		 * ex: PasswordBrowserController -> password_browser
		 * @param {String} format The return format [camel, under], by default camel for camelcased
		 * @return string
		 */
		'getAlias': function (type) {
			type = (typeof type == 'undefined') ? 'camel' : type;
			var returnValue = '';
			var alias = this.constructor.shortName.replace(/Controller$/, '');
			
			switch (type) {
				case 'under':
					returnValue = jQuery.String.underscore(alias);
				break;
				case 'camel':
				default:
					returnValue = alias;
				break;
			}
			
			return returnValue;
		},

		/**
		 * Get the controller class.
		 * @return {mad.controller.Controller}
		 */
		'getClass': function () {
			return this.constructor;
		}

	});

});