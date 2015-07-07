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
		 * Get item element
		 * @param item
		 * @returns {jQuery}
		 */
		'getItemElement': function(item) {
			return $('#' + item.id, this.element);
		},

		/**
		 * Insert an item in the tree
		 * @param {mad.model.Model} item The item to insert
		 * @param {string} refItemId The reference item id. By default the grid view object
		 * will choose the root as reference element.
		 * @param {string} position The position of the newly created item. You can pass in one
		 * of those strings: "before", "after", "inside", "first", "last". By dhe default value 
		 * is set to last.
		 * @return {jQuery}
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

			// map the given data to the desired format
			var mappedItem = this.getController().getMap().mapObject(item);
			this.getController().setViewData('itemClass', this.getController().getItemClass());
			this.getController().setViewData('item', item);
			this.getController().setViewData('mappedItem', mappedItem);

			// the item has children
			var hasChildren = mappedItem.children && mappedItem.children.length ? true : false;
			this.getController().setViewData('hasChildren', hasChildren);

			// some css classes has been defined on the item merge them
			var cssClasses = [];
			if (typeof mappedItem['cssClasses'] != 'undefined') {
				cssClasses = cssClasses.concat(mappedItem['cssClasses']);
			}
			this.getController().setViewData('cssClasses', cssClasses);

			var itemRender = mad.view.View.render(this.getController().options.itemTemplateUri, this.getController().getViewData());
			var $child = mad.helper.HtmlHelper.create($refList, position, itemRender);

			if (hasChildren) {
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
			var $item = this.getItemElement(item).remove();
		},


		/**
		 * Refresh an item in the tree
		 * @param {mad.model.Model} item The item to refresh
		 */
		'refreshItem': function (item, refItemId, position) {
			var self = this;
			var $item = this.getItemElement(item);

			// map the given data to the desired format
			var mappedItem = this.getController().getMap().mapObject(item);
			this.getController().setViewData('itemClass', this.getController().getItemClass());
			this.getController().setViewData('item', item);
			this.getController().setViewData('mappedItem', mappedItem);
			var hasChildren = mappedItem.children && mappedItem.children.length ? true : false;
			this.getController().setViewData('hasChildren', hasChildren);

			var itemRender = mad.view.View.render(this.getController().options.itemTemplateUri, this.getController().getViewData());
			$item.replaceWith(itemRender);

			if (hasChildren) {
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
		 * Unselect all.
		 */
		'unselectAll': function() {
			$('.row.selected', this.element).removeClass('selected');
		},

		/**
		 * An item has been selected
		 * @param {mixed} item The selected item instance or its id
		 * @return {void}
		 */
		'selectItem': function (item) {
			this.unselectAll();
			var $item = this.getItemElement(item);
			$('.row:first', $item).addClass('selected');
		},

		/**
		 * An item has been right selected
		 * @param {mixed} item The selected item instance or its id
		 * @return {void}
		 */
		'rightSelectItem': function (item) {
		},

		/**
		 * An item has been hovered
		 * @param {mixed} item The selected item instance or its id
		 * @return {void}
		 */
		'hoverItem': function (item, element, srcEvent) {
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @event item_selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'li .main-cell a click': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();

			var data = null,
				li = el.parents('li');
			if (this.getController().getItemClass()) {
				data = li.data(this.getController().getItemClass().fullName);
			} else {
				data = li[0].id;
			}

			this.element.trigger('item_selected', [data, ev]);
			return false;
		},

		/**
		 * An item has been selected
		 * @event item_right_selected
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
				this.element.trigger('item_right_selected', [data, ev]);
			}

			return false;
		},

		/**
		 * An item has been hovered
		 * @event item_hovered
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

			this.element.trigger('item_hovered', [data, ev]);
			return false;
		}

	});
});