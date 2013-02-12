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

	});
});