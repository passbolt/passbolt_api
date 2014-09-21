steal(
	'mad/controller/component/freeCompositeController.js',
	'mad/view/component/dialog.js',

	'mad/view/template/component/dialog.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.DialogController
	 * @inherits mad.controller.componentFreeCompositeController
	 * @parent mad.component
	 *
	 * @constructor
	 * Creates a new Dialog Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.DialogController}
	 */
	mad.controller.component.FreeCompositeController.extend('mad.controller.component.DialogController', /** @static */ {

		'defaults': {
			'singleton': null,
			'label': 'Dialog Controller',
			'viewClass': mad.view.component.Dialog,
			'cssClasses': ['popup'],
			'tag': 'div'
		}

	}, /** @prototype */ {
		
		// constructor like
		'init': function(el, options) {
			// if an instance of popup already exist return this instance
			if(mad.controller.component.DialogController.singleton != null) {
				return mad.controller.component.DialogController.singleton;
			}
			
			// create the DOM entry point for the popup
			var $el = mad.helper.HtmlHelper.create(
				mad.app.element,
				'first',
				'<div id="js_dialog" />'
			);
			
			// Changing the element force us to recall setup which is called before all init functions
			// and make the magic things (bind event ...)
			this.setup($el);
			this._super($el, options);
			mad.controller.component.DialogController.singleton = this; 
		},
		
		// destructor like
		'destroy': function() {
			mad.controller.component.DialogController.singleton = null;
			this._super();
		},
		
		/**
		 * Add a component to the popup container
		 * @param {Object} Class The class of the component to add
		 * @param {Object} options Option of the component
		 */
		'add': function(Class, options) {
			var component = this.addComponent(Class, options, 'js_dialog_content');
			component.start();
			return component;
		}
	});
});
