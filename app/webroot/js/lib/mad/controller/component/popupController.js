steal(
	'mad/controller/component/containerController.js',
	'mad/view/component/popup.js'
).then(function () {

	/*
	 * @class mad.controller.component.PopupController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.component
	 *
	 * @constructor
	 * Creates a new Popup Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.PopupController}
	 */
	mad.controller.component.ContainerController.extend('mad.controller.component.PopupController', /** @static */ {

		'defaults': {
			'label': 'Popup Container Controller',
			'viewClass': mad.view.component.Popup,
			'cssClasses': ['popup'],
			'tag': 'div'
		},

		/**
		 * Instance of the popup
		 */
		'singleton': null,

		/**
		 * Get a new popup container
		 * @param {array} options Options to pass to the popup constructor
		 * @return {mad.controller.component.PopupController}
		 */
		'getPopup': function (options) {
			if(mad.controller.component.PopupController.singleton != null) {
				return mad.controller.component.PopupController.singleton;
			}
			// create the DOM entry point for the popup
			var $el = mad.helper.HtmlHelper.create(
				mad.app.element,
				'first',
				'<div id="js_popup" />'
			);
			// instantiate the popup
			mad.controller.component.PopupController.singleton = new mad.controller.component.PopupController($el, options);
			return mad.controller.component.PopupController.singleton;
		}

	}, /** @prototype */ {
		
		// constructor like
		'init': function(el, options) {
			// if an instance of popup already exist return this instance
			if(mad.controller.component.PopupController.singleton != null) {
				return mad.controller.component.PopupController.singleton;
			}
			this._super(el, options);
		},
		
		// destructor like
		'destroy': function() {
			delete mad.controller.component.PopupController.singleton;
			this._super();
		},
		
		/**
		 * Add a component to the popup container
		 * @param {Object} Class The class of the component to add
		 * @param {Object} options Option of the component
		 */
		'add': function(Class, options) {
			return this.addComponent(Class, options, 'js_popup_content')
				.start();
		}
	});
});