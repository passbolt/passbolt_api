steal(
	'mad/controller/component/freeCompositeController.js',
	'mad/view/component/popup.js'
).then(function () {

	/*
	 * @class mad.controller.component.PopupController
	 * @inherits mad.controller.componentFreeCompositeController
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
	mad.controller.component.FreeCompositeController.extend('mad.controller.component.PopupController', /** @static */ {

		'defaults': {
			'singleton': null,
			'label': 'Popup Container Controller',
			'viewClass': mad.view.component.Popup,
			'cssClasses': ['popup'],
			'tag': 'div'
		},

	}, /** @prototype */ {
		
		// constructor like
		'init': function(el, options) {
			// if an instance of popup already exist return this instance
			if(mad.controller.component.PopupController.singleton != null) {
				return mad.controller.component.PopupController.singleton;
			}
			
			// create the DOM entry point for the popup
			var $el = mad.helper.HtmlHelper.create(
				mad.app.element,
				'first',
				'<div id="js_popup" />'
			);
			
			// Changing the element force us to recall setup which is called befor all init functions
			// and make the magic things (bind event ...)
			this.setup($el);
			this._super($el, options);
			mad.controller.component.PopupController.singleton = this; 
		},
		
		// destructor like
		'destroy': function() {
			mad.controller.component.PopupController.singleton = null;
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