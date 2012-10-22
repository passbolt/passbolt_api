steal(
	MAD_ROOT + '/controller/component/menuController.js',
	MAD_ROOT + '/view/component/menu/dropDownMenu.js',
	MAD_ROOT + '/view/template/component/menu/dropDownMenu.ejs'
).then(function ($) {

	/**
	 * @class mad.controller.component.DropDownMenuController
	 * @inherits {mad.controller.component.MenuController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a Drop Down Menu Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.DropDownMenuController}
	 */
	mad.controller.component.MenuController.extend('mad.controller.component.DropDownMenuController', /** @static */ {

		'defaults': {
			'label': 'Drop Down Menu Controller Component',
			'viewClass': mad.view.component.menu.DropDownMenu,
			'templateUri': '//' + MAD_ROOT + '/view/template/component/tree.ejs',
			'itemTemplateUri': '//' + MAD_ROOT + '/view/template/component/menu/dropDownMenu.ejs',
			'cssClasses': ['dropdownmenu'],
			'callbacks': {
				'item_selected': null,
				'item_right_selected': null,
				'item_hovered': null
			}
		},

		// listen to the following custom view event
		'listensTo': [
			'item_selected',
			'item_right_selected',
			'item_hovered',
			'item_opened',
			'item_closed'
		]

	}, /** @prototype */ {

		/**
		 * Open an item
		 * @param {string} itemId The target item to open
		 * @return {void}
		 */
		'open': function (itemId) {
			this.view.open(itemId);
		},

		/**
		 * Close an item
		 * @param {string} itemId The target item to close
		 * @return {void}
		 */
		'close': function (itemId) {
			this.view.close(itemId);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been uncollapsed
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_opened': function (element, event, itemId) {
			this.open(itemId);
		},

		/**
		 * An item has been uncollapsed
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_closed': function (element, event, itemId) {
			this.close(itemId);
		}

	});
});