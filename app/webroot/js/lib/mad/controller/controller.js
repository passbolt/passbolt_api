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

		'defaults': { }

	}, /** @prototype */ {

		// Class constructor
		'init': function (el, options) {
			// set the options
			this.options = $.extend(true, {}, this.options, options);
			// if the element does not carry the id, use the id given in options or generate a new one
			if (this.getId() == '') {
				var id = this.options.id || uuid();
				this.element.attr('id', id);
			}
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
			return this.element[0].id;
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
		}

	});

});