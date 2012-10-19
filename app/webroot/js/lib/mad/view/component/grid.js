steal(
	MAD_ROOT + '/view',
	MAD_ROOT + '/view/template/component/grid.ejs'
).then(function ($) {

	/*
	 * @class mad.view.component.Grid
	 * @inherits mad.view.View
	 * 
	 * Our implementation of the view grid component
	 * 
	 * @constructor
	 * Creates a grid view
	 * @return {mad.view.component.Grid}
	 */
	mad.view.View.extend('mad.view.component.Grid', /** @static */ {  }, /** @prototype */ {

		// Constructor like
		'init': function (controller, options) {
			this._super(controller, options);
		},

		/**
		 * Empty the grid
		 * @return {void}
		 */
		'empty': function () {
			$('tbody tr', this.element).remove();
		},

		/**
		 * Hide Column
		 * @param {string} columnName The column name to hide
		 * @return {void}
		 */
		'hideColumn': function (columnName) {
			$('.js_grid_column_' + columnName, this.element).hide();
		},

		/**
		 * Show Column
		 * @param {string} columnName The column name to show
		 * @return {void}
		 */
		'showColumn': function (columnName) {
			$('.js_grid_column_' + columnName, this.element).show();
		},

		/**
		 * Select an item
		 * @param {string} itemId The item to select
		 * @return {void}
		 */
		'selectItem': function (itemId) {
			$('#' + itemId, this.element).addClass('js_grid_selected_item');
		},

		/**
		 * Unselect an item
		 * @param {string} itemId The item to unselect
		 * @return {void}
		 */
		'unselectItem': function (itemId) {
			$('#' + itemId, this.element).removeClass('js_grid_selected_item');
		},

		/**
		 * Delete an item in the grid
		 * @param {string} itemId The item to delete
		 * @return {void}
		 */
		'deleteItems': function (itemId) {
			$('#' + itemId, this.element).remove();
		},

		/**
		 * Insert items in the grid
		 * @param {array} items The array of items to insert in the grid
		 * @param {string} position The position to insert the new items.
		 * Allowed first, last, before, after
		 * @param {string} refId The reference item id to position the new ones
		 * @return {void}
		 */
		'insertItems': function (items, refItemId, position) {
			position = position || 'last';
			var $row = null,
				$ref = refItemId && (position == 'after' || position == 'before')? $('#' + refItemId, this.element) : $('tbody', this.$grid);

			items = !$.isArray(items) ? [items] : items;
			var mappedItems = this.controller.map.mapObjects(items);
			if (!$.isArray(mappedItems)) {
				mappedItems = [mappedItems];
			}

			for (var i in items) {
				var mappedItem = mappedItems[i],
					rowContent = '<tr id="' + mappedItem.id + '">';

				// insert column data
				for(var j in this.controller.options.columnModel) {
					var columnModel = this.controller.options.columnModel[j],
						cssClass = 'js_grid_column_' + columnModel.name,
						cellValue = null;

					// A column adapater function is provided
					if(columnModel.valueAdapter) {
						cellValue = columnModel.valueAdapter(mappedItem[columnModel.name], mappedItem, columnModel, i);
					}
					// A widget will take care of the cell rendering
					else if(columnModel.widget || columnModel.cellAdapter) {
						cellValue = '';
					}
					// Else display the column value
					else {
						cellValue = mappedItem[columnModel.name];
					}

					// append the cell to the row
					rowContent += '<td class="' + cssClass + '"><span>' + cellValue + '</span></td>';
				}

				rowContent += '</tr>'

				// insert the row
				switch(position) {
				case 'first':
					$row = $(rowContent).prependTo($ref);
					break;

				case 'last':
					$row = $(rowContent).appendTo($ref);
					break;

				case 'before':
					$row = $(rowContent).insertBefore($ref);
					break;

				case 'after':
					$row = $(rowContent).insertAfter($ref);
					break;
				}
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
		'tbody tr click': function (element, event) {
			this.element.trigger('item_selected', element[0].id);
		},
		
		/**
		 * An item has been hovered
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'tbody tr hover': function (element, event) {
			this.element.trigger('item_hovered', element[0].id);
		}

	});
});