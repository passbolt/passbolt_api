steal(
	MAD_ROOT + '/controller/component/dropDownMenuController.js'
).then(function ($) {

	/**
	 * @class mad.controller.component.ContextualMenuController
	 * @inherits {mad.controller.component.DropDownMenuController}
	 * @parent index
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

		'destroy': function () {
			$('#js_contextual_menu_ref_point').remove();
		},

		'render': function () {
			this._super();

			$refPoint = $('<div id="js_contextual_menu_ref_point" style="position:absolute; top:' + (this.options.mouseY-5) + 'px; left:' + (this.options.mouseX-5) + 'px">');
			$('body').prepend($refPoint);
			this.element.position({
				my: "left top",
				at: "right bottom",
				of: $refPoint
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * The mouse leave the contextual menu
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'mouseleave': function (element, event) {
			this.goToHell();
		}

	});
});