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
			'label': 'Dialog Controller',
			'viewClass': mad.view.component.Dialog,
			'cssClasses': ['dialog-wrapper'],
			'tag': 'div'
		},

		/**
		 * Close the latest dialog.
		 */
		'closeLatest': function() {
			$('.dialog-wrapper:last').remove();
		}

	}, /** @prototype */ {
		
		// constructor like
		'init': function(el, options) {
			// Create the DOM entry point for the dialog
			var refElt = mad.app.element,
				position = 'first';

			// If a dialog already exist, position the new one right after.
			var $existingDialog = $('.dialog-wrapper:last');
			if ($existingDialog.length) {
				refElt = $existingDialog;
				position = "after";
			}

			// Insert the element in the page DOM.
			var $el = mad.helper.HtmlHelper.create(refElt, position, '<div />');

			// Changing the element force us to recall the setup which is called before all init functions
			// and make the magic things (bind event ...)
			this.setup($el, options);
			this._super($el, options);
		},

		/**
		 * Add a component to the dialog container
		 * @param {mad.controller.ComponentController} Class The class of the component to add, or the html to
		 *   display.
		 * @param {Object} options Option of the component
		 */
		'add': function(Class, options) {
			if (typeof options == 'undefined' || options == null) {
				options = {};
			}

			var component = this.addComponent(Class, options, 'js_dialog_content');
			component.start();

			return component;
		},

		/**
		 * Set the title
		 * @param {string} title The new title
		 */
		'setTitle': function(title) {
			this.view.setTitle(title);
		}
	});
});
