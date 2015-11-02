steal(
	'mad/controller/component/treeController.js',
	'mad/view/template/component/menu/menuItem.ejs',
	'mad/model/action.js'
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
			'cssClasses': ['menu'],

			// The template to use to render each action.
			'itemTemplateUri': 'mad/view/template/component/menu/menuItem.ejs',
			// The class which represent the item.
			'itemClass': mad.model.Action,
			// Mapping of the items for the view.
			'map': new mad.object.Map({
				'id': 'id',
				'label': 'label',
				// @todo : be carefull, for now if no cssClasses defined while creating the action.
				// @todo : this mapping is not done, and the state is not added to css classes.
				'cssClasses': {
					'key': 'cssClasses',
					'func': function(value, map, item, mappedValues) {
						var mappedValue = $.merge([], value);
						// If a state is defined for the given item.
						// Add the state to the css classes.
						if (typeof item.state != 'undefined') {
							mappedValue = $.merge(mappedValue, item.state.current);
						}
						return mappedValue.join(' ');
					}
				},
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			})
		}

	}, /** @prototype */ {

		/**
		 * Set the item state.
		 * @param id The item id.
		 * @param stateName The state to set.
		 */
		'setItemState': function(id, stateName) {
			for (i in this.options.items) {
				if (this.options.items[i].id == id) {
					this.options.items[i].state.setState(stateName);
					this.refreshItem(this.options.items[i]);
				}
			}
		},

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
			// If an action has been associated, and the item is not disabled.
			if(action = item.getAction() && !item.state.is('disabled')) {
				item.action(this);
			}
		}

	});
});
