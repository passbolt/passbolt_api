steal(
	MAD_ROOT + '/controller/component/treeController.js',
	MAD_ROOT + '/view/template/component/menu/menuItem.ejs'
).then(function ($) {

	/**
	 * @class mad.controller.component.MenuController
	 * @inherits {mad.controller.component.TreeController}
	 * @parent index
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
			'itemTemplateUri': MAD_ROOT + '/view/template/component/menu/menuItem.ejs',
			'cssClasses': ['menu'],
			'map': new mad.object.Map({
				'id': 'MenuItem.id',
				'label': 'MenuItem.label',
				'cssClasses': 'MenuItem.cssClasses',
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
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_selected': function (element, event, itemId) {
			this._super(element, event, itemId);
			// get the menuItem
			var menuItem = mad.model.Model.searchOne(this.state.data, 'MenuItem.id', itemId);
			// execute the associated action
			menuItem.MenuItem.action(this);
		}

	});
});