steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.component.Tree
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('mad.view.component.Tree', /** @static */ {

	}, /** @prototype */ {

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
			var $refElement = refItemId ? this.element.find('#' + refItemId) : null,
				$refList = $refElement ? $refElement.find('ul:first') : this.element,
				self = this;

			// if no refList found, create it
			if (!$refList.length) {
				$refElement.append('<ul/>');
				$refList = $refElement.find('ul:first');
			}

			// map the jmvc model objects into the desired format
			var mappedItem = this.getController().getMap().mapObject(item);
			mappedItem.hasChildren = mappedItem.children && mappedItem.children.length ? true : false;
			mappedItem.item = item;
			mappedItem.itemClass = this.getController().getItemClass();

			var itemRender = mad.view.View.render(this.getController().options.itemTemplateUri, mappedItem);
			var $child = null;
			if(position == 'first') {
				$child = $(itemRender).prependTo($refList);
			}
			else {
				$child = $(itemRender).appendTo($refList);
			}

			if (mappedItem.hasChildren) {
				can.each(item.children, function (item, i) {
					self.insertItem(item, mappedItem.id, 'last');
				});
			}
			return $child;
		},

		/**
		 * Remove an item from the tree
		 * @param {mad.model.Model} item The target item to remove
		 * @return {void}
		 */
		'removeItem': function (item) {
			var $item = $('#' + item.id, this.element).remove();
		},


		/**
		 * Refresh an item in the tree
		 * @param {mad.model.Model} item The item to refresh
		 */
		'refreshItem': function (item, refItemId, position) {
			var self = this;
			var $item = $('#' + item.id, this.element);

			// map the jmvc model objects into the desired format
			var mappedItem = this.getController().getMap().mapObject(item);
			mappedItem.hasChildren = mappedItem.children && mappedItem.children.length ? true : false;
			mappedItem.item = item;
			mappedItem.itemClass = this.getController().getItemClass();

			var itemRender = mad.view.View.render(this.getController().options.itemTemplateUri, mappedItem);
			$item.replaceWith(itemRender);

			if (mappedItem.hasChildren) {
				can.each(item.children, function (item, i) {
					self.insertItem(item, mappedItem.id, 'last');
				});
			}
			return $item;
		},

		/**
		 * Reset the view by removing all the items
		 * @return {void}
		 */
		'reset': function () {
			$('li', this.element).remove();
		},

		/**
		 * An item has been selected
		 * @event item_selected
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} srcEvent The jQuery source event
		 * @return {void}
		 */
		'itemSelected': function (item, element, srcEvent) {
			this.element.trigger('item_selected', [item, srcEvent]);
		},

		/**
		 * An item has been right selected
		 * @event item_right_selected
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} srcEvent The jQuery source event
		 * @return {void}
		 */
		'itemRightSelected': function (item, element, srcEvent) {
			element.trigger('item_right_selected', [item, srcEvent]);
		},

		/**
		 * An item has been hovered
		 * @event item_hovered
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLElement} element The element the event occured on
		 * @return {void}
		 */
		'itemHovered': function (item, element, srcEvent) {
			this.element.trigger('item_hovered', [item, srcEvent]);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'li a click': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();

			var data = null,
				li = el.parents('li');
			if (this.getController().getItemClass()) {
				data = li.data(this.getController().getItemClass().fullName);
			} else {
				data = li[0].id;
			}

			this.itemSelected(data, el, ev);
			return false;
		},

		/**
		 * An item has been selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'li a contextmenu': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();

			if (ev.which == 3) {
				var data = null,
					li = el.parents('li');
				if (this.getController().getItemClass()) {
					data = li.data(this.getController().getItemClass().fullName);
				} else {
					data = li[0].id;
				}
				this.itemRightSelected(data, el, ev);
			}

			return false;
		},

		/**
		 * An item has been hovered
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'li a hover': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();

			var data = null,
				li = el.parents('li');
			if (this.getController().getItemClass()) {
				data = li.data(this.getController().getItemClass().fullName);
			} else {
				data = li[0].id;
			}
			this.itemHovered(data, el, ev);
			return false;
		}

	});
});