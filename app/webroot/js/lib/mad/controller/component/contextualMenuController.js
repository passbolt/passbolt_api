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

		'destroy': function () {
			$('#js_contextual_anchor', this.element).remove();
		},

		/**
		 * Render the component. Override the parent behavior to automatically position
		 * the contextual menu functons of optional parameters mouseX and mouseY.
		 * @see {mad.view.View}
		 * @param {array} options Associative array of options
		 * @param {boolean} options.display Display the rendered component. If true
		 * the rendered component will be push in the DOM else the rendered component
		 * will be stored in the instance's variable renderedView
		 * @return {mixed} Return true if the method does not encountered troubles else
		 * return false. If the option display is set to false, return the rendered view
		 */
		'render': function () {
			this._super();

			// we make the view's job in the controller
			// Insert the reference point in the DOM
			var refPointHtml = '<div id="js_contextual_anchor" style="position:absolute;"></div>'
			var refPoint = mad.helper.HtmlHelper.create($('body'), 'first', refPointHtml);
			var $refPoint = $(refPoint);

			$refPoint.css({
				left: this.options.mouseX,
				top: this.options.mouseY
			});
			// Position the contextual menu functions of the reference point and the given options
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
		 * @param {HTMLElement} el The element the event occured on
		 * @param {Event} ev The jQuery event
		 * @return {void}
		 */
		'mouseleave': function (el, ev) {
			this.remove();
		}

	});
});