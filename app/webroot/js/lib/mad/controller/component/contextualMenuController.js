steal(
	'mad/controller/component/dropDownMenuController.js'
).then(function () {

	/**
	 * @class mad.controller.component.ContextualMenuController
	 * @inherits {mad.controller.component.DropDownMenuController}
	 * @parent mad.controller.component
	 * 
	 * @constructor
	 * Instanciate a Contextual Menu Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.ContextualMenuController}
	 */
	mad.controller.component.DropDownMenuController.extend('mad.controller.component.ContextualMenuController', /** @static */ {

	}, /** @prototype */ {

		// constructor like
		'init': function(el, options) {
			// if no element given
			if(el == null || !el.length) {
				// if a previous contextual menu is still displayed, remove it
				if($('#js_contextual_menu').length != 0) {
					$('#js_contextual_menu').remove();
				}

				// create the DOM entry point for the popup
				var $el = mad.helper.HtmlHelper.create(
					mad.app.element,
					'first',
					'<div id="js_contextual_menu" />'
				);

				// Changing the element force us to recall setup which is called before all init functions
				// and make the magic things happen (bind events ...)
				this.setup($el);
			}

			this._super($el, options); 
		},

		/**
		 * After start hook.
		 * Position the contextual menu functions of the given mouse position
		 */
		'afterStart': function () {
			this._super();
			this.view.position({'mouse': {'x':this.options.mouseX-5, 'y':this.options.mouseY-5}});
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * The mouse leave the contextual menu
		 * @param {HTMLElement} el The element the event occured on
		 * @param {Event} ev The jQuery event
		 * @return {void}
		 */
		'mouseleave': function (el, ev) {
			this.remove();
		}

	});
});