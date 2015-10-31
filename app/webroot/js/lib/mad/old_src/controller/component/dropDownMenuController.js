steal(
	'mad/controller/component/menuController.js',
	'mad/view/component/menu/dropDownMenu.js',
	'mad/view/template/component/menu/dropDownMenu.ejs'
).then(function () {

	/**
	 * @class mad.controller.component.DropDownMenuController
	 * @inherits {mad.controller.component.MenuController}
	 * @parent mad.controller.component
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
			'templateUri': 'mad/view/template/component/tree.ejs',
			'itemTemplateUri': 'mad/view/template/component/menu/dropDownMenu.ejs',
			'cssClasses': ['dropdownmenu'],
			'callbacks': {
				'item_selected': null,
				'item_right_selected': null,
				'item_hovered': null
			}
		}

	}, /** @prototype */ {

		/**
		 * Open an item
		 * @param {mad.model.Model} item The target item to open
		 * @return {void}
		 */
		'open': function (item) {
			this.view.open(item);
		},

		/**
		 * Close an item
		 * @param {mad.model.Model} item The target item to close
		 * @return {void}
		 */
		'close': function (item) {
			this.view.close(item);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been uncollapsed
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.model.Model} item The target item
		 * @return {void}
		 */
		' item_opened': function (el, ev, item) {
			this.open(item);
		},

		/**
		 * An item has been uncollapsed
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.model.Model} item The target item
		 * @return {void}
		 */
		' item_closed': function (el, ev, item) {
			this.close(item);
		}

	});
});