steal(
	MAD_ROOT + '/view'
).then(function ($) {

	/*
	 * @class mad.view.component.Tree
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('mad.view.component.Tree', /** @static */ {

	}, /** @prototype */ {

		/**
		 * The map to transform JMVC model object into jstree node
		 * @type {mad.object.Map}
		 */
		'map': null,

		// Constructor like
		'init': function (controller, options) {
			this._super(controller, options);
		},

		/**
		 * Insert an item in the tree
		 * @param {mad.model.Model} item The item to insert
		 * @param {string} refItemId The reference item id. By default the grid view object
		 * will choose the root as reference element.
		 * @param {string} position The position of the newly created item. You can pass in one
		 * of those strings: "before", "after", "inside", "first", "last". By dhe default value 
		 * is set to last.
		 * @return {void}
		 * @todo does not require a map in this case
		 */
		'insertItem': function (item, refItemId, position) {
			position = position || 'last';
			var $ref = refItemId ? this.element.find('#' + refItemId + ' ul:first') : this.element;
			// map the jmvc model objects into the desired format
			var mappedItem = this.map.mapObject(item);
			mappedItem.hasChildren = item.children && item.children.length ? true : false;

			var itemRender = $.View(this.controller.options.itemTemplateUri, mappedItem);
			var $child = $(itemRender).appendTo($ref);

			for (var i in mappedItem.children) {
				$('<ul/>').appendTo($child);
				this.insertItem(item.children[i], mappedItem.id, 'last');
			}
		},
		
		/**
		 * Delete an item from the tree
		 * @param {string} itemId The target item to delete
		 * @return {void}
		 */
		'deleteItem': function (itemId) {
			var $item = $('#' + itemId, this.element).remove();
		},

		/**
		 * An item has been selected
		 * @event item_selected
		 * @param {string} itemId The right selected item id
		 * @param {HTMLElement} element The element the event occured on
		 * @return {void}
		 */
		'itemSelected': function (itemId, element, srcEvent) {
			this.element.trigger('item_selected', [itemId, srcEvent]);
		},

		/**
		 * An item has been right selected
		 * @event item_right_selected
		 * @param {string} itemId The right selected item id
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} srcEvent The jQuery source event
		 * @return {void}
		 */
		'itemRightSelected': function (itemId, element, srcEvent) {
			element.trigger('item_right_selected', [itemId, srcEvent]);
		},

		/**
		 * An item has been hovered
		 * @event item_hovered
		 * @param {string} itemId The right selected item id
		 * @param {HTMLElement} element The element the event occured on
		 * @return {void}
		 */
		'itemHovered': function (itemId, element, srcEvent) {
			this.element.trigger('item_hovered', [itemId, srcEvent]);
		},

		/**
		 * Load the tree with an additionnal node at the specific position (ref + position)
		 * @param {object|array} data The data which represent the node
		 * @todo the mapping could be done in the view ?
		 */
		'load': function (data) {
			var mappedData;

			if ($.isArray(data)) {
				for (var i in data) {
					this.insertItem (data[i], null, 'last');
				}
			} else {
				this.insertItem (data, null, 'last');
			}
		},

		/**
		 * Render the jstree component
		 * @return {void}
		 */
		'render': function () {
			this._super();
		},
		
		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'li click': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			this.itemSelected(element[0].id, element);
		},

		/**
		 * An item has been selected
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'li contextmenu': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			if(event.which == 3){
				this.itemRightSelected(element[0].id, element, event);
			}
			return false;
		},

		/**
		 * An item has been hovered
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'li hover': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			this.itemHovered(element[0].id, element);
			return false;
		}

	});
});