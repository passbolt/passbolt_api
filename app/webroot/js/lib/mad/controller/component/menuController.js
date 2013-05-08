steal(
	'mad/controller/component/treeController.js',
	'mad/view/template/component/menu/menuItem.ejs'
).then(function () {

	/**
	 * @class mad.controller.component.MenuController
	 * @inherits {mad.controller.component.TreeController}
	 * @parent mad.controller.component
	 * 
	 * ## Example
	 * @demo lib/mad/demo/controller/component/menu_controller.html
	 * 
	 * @constructor
	 * Instanciate a Menu Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.MenuController}
	 */
	mad.controller.component.TreeController.extend('mad.controller.component.MenuController', /** @static */ {

		'defaults': {
			'label': 'MenuController',
			// The template to use to render each action
			'itemTemplateUri': 'mad/view/template/component/menu/menuItem.ejs',
			'cssClasses': ['menu'],
			'itemClass': mad.model.Action,
			// The map to use to map the model to the expected view format
			'map': new mad.object.Map({
				'id': 'id',
				'label': 'label',
				'cssClasses': 'cssClasses',
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			})
		}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {string} item The selected item
		 * @return {void}
		 */
		' item_selected': function (el, ev, item) {
			this._super(el, ev, item);
			// execute the associated action
			item.action(this);
		}

	});
});