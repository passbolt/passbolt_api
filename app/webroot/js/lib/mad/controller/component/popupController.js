steal(
	'mad/controller/component/containerController.js'
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
			'cssClasses': ['popup'],
			'tag': 'div'
		},

		/**
		 * Get a new popup container
		 * @param {array} options Options to pass to the popup constructor
		 * @return {mad.controller.component.PopupController}
		 */
		'getPopup': function (popupOptions, ComponentClass, componentOptions) {
			// create the popup component just behind the app controller tag
			var popup = mad.helper.ComponentHelper.create(
				mad.app.element,
				'first',
				mad.controller.component.PopupController,
				popupOptions
			);
			// render the popup
			popup.render();
			// If a component class is given add it to the popup
			if (ComponentClass) {
				var component = popup.addComponent(ComponentClass, componentOptions, 'js_popup_content');
				component.render();
			}
			return popup;
		}

	}, /** @prototype */ {


		/**
		 * Render the component
		 * @see {mad.controller.componentController}
		 */
		'render': function (options) {
			var returnValue = this._super();
			this.element.find('.js_popup_content_container').position({
				my: "center center",
				at: "center center",
				of: this.element
			});
			return returnValue;
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the user interaction click with the close button
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.js_popup_close click': function (el, ev) {
			this.element.remove();
		}

	});
});