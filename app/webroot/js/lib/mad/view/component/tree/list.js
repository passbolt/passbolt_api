steal(
	MAD_ROOT + '/view/component/tree.js'
).then(function ($) {

	/*
	 * @class mad.view.component.tree.List
	 * @inherits mad.view.component.Tree
	 * 
	 * Our representation of the list tree view
	 * 
	 * @constructor
	 * Instanciate a new list view
	 * @return {mad.view.component.tree.List}
	 */
	mad.view.component.Tree.extend('mad.view.component.tree.List', /** @static */ {

	}, /** @prototype */ {

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

			var itemRender = $.View(this.controller.options.itemTemplateUri, mappedItem);
			var $child = $(itemRender).appendTo($ref);

			for (var i in mappedItem.children) {
				$('<ul/>').appendTo($child);
				this.insertItem(item.children[i], mappedItem.id, 'last');
			}
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