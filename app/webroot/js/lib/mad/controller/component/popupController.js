steal(
	MAD_ROOT + '/controller/component/containerController.js'
).then(function ($) {

	/*
	 * @class mad.controller.component.PopupController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.component
	 * 
	 * 
	 * 
	 * @constructor
	 * Creates a new Popup Controller
	 * @return {mad.controller.component.PopupController}
	 */
	mad.controller.component.ContainerController.extend('mad.controller.component.PopupController', /** @static */ {

		'defaults': {
			'label': 'Popup Container Controller',
			'cssClasses': 'js_popup'
		},

		/**
		 * Get a new popup container
		 * @param {array} options Options to pass to the popup constructor
		 * @return {mad.controller.component.PopupController}
		 */
		'get': function (popupOptions, ComponentClass, componentOptions) {
			var popupId = uuid();
			var $popup = $('<div id="' + popupId + '" class="js_popup"/>').appendTo(mad.app.element);
			var popup = new mad.controller.component.PopupController($popup, popupOptions).render();
			// If a component class is given add it to the popup
			if (ComponentClass) {
				popup.addComponent(ComponentClass, componentOptions, 'js_popup_content');
			}
			return popup;
		}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the user interaction click with the close button
		 * @return {void}
		 */
		'.js_popup_close click': function () {
			this.element.remove();
		}

	});
});