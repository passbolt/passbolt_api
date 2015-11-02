steal(
	'mad/view',
	'mad/view/template/component/grid.ejs'
).then(function () {

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
		 * Reset the grid
		 * @return {void}
		 */
		'reset': function () {
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
		 * @param {mixed} item The selected item instance or its id
		 * @return {void}
		 */
		'selectItem': function (item) {
			$('#' + item.id, this.element).addClass('selected');
		},

		/**
		 * Unselect an item
		 * @param {mixed} item The unselected item instance or its id
		 * @return {void}
		 */
		'unselectItem': function (item) {
			$('#' + item.id, this.element).removeClass('selected');
		},

		/**
		 * Remove an item to the grid
		 * @param {mad.model.Model} item The item to remove
		 * @return {void}
		 */
		'removeItem': function (item) {
			var el = $('#' + item.id, this.element);
			el.remove();
		},

		/**
		 * Render a row for a given item
		 * @param {mad.model.Model} item The item to use to render the row
		 * @return {void}
		 */
		'_renderRow': function (item) {
			var returnValue = null,
				// the mapped item
				mappedItem = this.getController().getMap().mapObject(item),
				// the cells data (columnName -> value)
				values = [],
				// the cells titles data (columnName -> value)
				titles = [],
				// the grid column models
				columnModels = this.getController().getColumnModel();

			// insert column data
			for(var i in columnModels) {
				// the column model which describe the current column
				var columnModel = columnModels[i],
					// the cell value
					cellValue = null,
					// the cell title value
					titleValue = null;

				// A column adapater is provided, use it to adapt the cell value
				if(columnModel.valueAdapter) {
					cellValue = columnModel.valueAdapter(mappedItem[columnModel.name], mappedItem, item, columnModel);
				} else if(columnModel.widget || columnModel.cellAdapter) {
					// A widget will take care of the cell rendering
					cellValue = '';
				} else {
					// Else, use the mapped item value as it is as cell value
					cellValue = mappedItem[columnModel.name];
				}
				
				values[columnModel.name] = cellValue;

				// A column title adapter is provided, use it to adapt the title value
				if(columnModel.titleAdapter) {
					titleValue = columnModel.titleAdapter(mappedItem[columnModel.name], mappedItem, item, columnModel);
				} else {
					titleValue = cellValue;
				}

				titles[columnModel.name] = titleValue;
			}

			// render the row item
			returnValue = mad.view.View.render(this.getController().options.itemTemplateUri, {
				'item': item,
				'id': mappedItem.id,
				'columnModels': columnModels,
				'values': values,
				'titles': titles
			});

			return returnValue;
		},

		/**
		 * Insert an intem in the grid
		 * @param {mad.model.Model} item The item to insert in the grid
		 * @param {string} position The position to insert the new item
		 * Allowed first, last, before, after
		 * @param {string} refId The reference item id to position the new ones
		 * @return {void}
		 */
		'insertItem': function (item, refItemId, position) {
			// By default position the new element inside as final element
			position = position || 'last';
			// the reference HTML Element to use to position the new one
			var $ref = refItemId ? $('#' + refItemId, this.element) : $('tbody', this.element),
				// the mapped item
				mappedItem = this.getController().getMap().mapObject(item),
				// the row html fragment to insert
				row = '';

			// render the row
			row = this._renderRow(item);

			// insert the raw html fragment in the grid
			return mad.helper.HtmlHelper.create($ref, position, row);
		},

		/**
		 * Refresh item
		 * @param {mad.model.Model} item The item to refresh
		 * @return {void}
		 */
		'refreshItem': function (item) {
			// the mapped item
			var mappedItem = this.getController().getMap().mapObject(item),
				// the row html fragment to insert
				row = '',
				// the current row
				$current = $('#' + mappedItem.id, this.element);

			// render the row
			row = this._renderRow(item);

			// insert the raw html fragment in the grid
			mad.helper.HtmlHelper.create($current, 'replace_with', row);
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
		'tbody tr click': function (el, ev) {
			var data = null;

			if (this.getController().getItemClass()) {
				data = el.data(this.getController().getItemClass().fullName);
			} else {
				data = el[0].id;
			}
			this.element.trigger('item_selected', [data, ev]);
		},
		
		/**
		 * An item has been hovered
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'tbody tr hover': function (el, ev) {
			var data = null;
			if (this.getController().getItemClass()) {
				data = el.data(this.getController().getItemClass().fullName);
			} else {
				data = el[0].id;
			}
			this.element.trigger('item_hovered', [data, ev]);
		}

	});
});