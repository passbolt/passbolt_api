steal(
	'jquery/controller',
	'mad/controller/componentController.js',
	'mad/view/component/grid.js',
	'mad/object/map.js'
).then(function () {

	/*
	 * @class mad.controller.component.GridController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * @demo ./mad/demo/controller/grid_controller.html
	 * @see mad.view.component.Grid
	 * 
	 * The Grid class Controller is our implementation of the UI component grid.
	 * 
	 * @constructor
	 * Creates a new Grid Controller Component
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.GridController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.GridController', /** @static */	{

		'defaults': {
			'label': 'Grid Component',
			'viewClass': mad.view.component.Grid,
			'cssClasses': ['mad_grid'],
			'templateUri': 'mad/view/template/component/grid.ejs',
			'itemTemplateUri': 'mad/view/template/component/grid/gridItem.ejs',

			// The class of the item
			'itemClass': null,
			// the grid column names
			'columnNames': [],
			// the grid column model
			'columnModel': [],
			// the map to use to map JMVC model to the grid data model
			'map': null,
			// the top tag of the grid
			'tag': 'table',
			// callbacks associated to the events which could occured
			'callbacks': {
				'item_selected': null,
				'item_hovered': null
			}
		}

	}, /** @prototype */ {

		/**
		 * The map to transform JMVC model object into jqgrid understable format
		 * @type {mad.object.Map}
		 */
		'map': null,

		// Construcor like
		'init': function (el, options) {
			this._super(el, options);
			this.map = this.options.map;
			this.itemClass= options.itemClass || null;
			this.setViewData('items', []);
			this.setViewData('columnModel', this.options.columnModel);
			this.setViewData('columnNames', this.options.columnNames);
		},

		/**
		 * Empty the grid
		 * @return {void}
		 */
		'empty': function () {
			// @todo Check after this operation if the widget are well destroyed.
			// The hypothesis let's believe me than the remove function will deeply removed
			// each element and launch the destroy function of each component controller
			this.view.empty();
		},

		/**
		 * Remove an item to the grid
		 * @param {mad.model.Model} item The item to remove
		 * @return {void}
		 */
		'removeItem': function (item) {
			// Remove the item to the view
			this.view.removeItem(item);
		},

		/**
		 * Insert an item in the grid
		 * @param {mad.model.Model} item The item to insert
		 * @param {string} refItemId The reference item id. By default the grid view object
		 * will choose the root as reference element.
		 * @param {string} position The position of the newly created item. You can pass in one
		 * of those strings: "before", "after", "inside", "first", "last". By dhe default value 
		 * is set to last.
		 * @return {void}
		 */
		'insertItem': function (item, refItemId, position) {
			var self = this;
			var mappedItem = this.map.mapObject(item);

			// insert the item in the view
			this.view.insertItem(item, refItemId, position);

			// apply a widget to cells following the columns model
			for(var j in this.options.columnModel) {
				var columnModel = this.options.columnModel[j];

				if(columnModel.cellAdapter) {
					var itemId = mappedItem.id;
					var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' span');
					var cellValue = mappedItem[columnModel.name];
					columnModel.cellAdapter($cell, cellValue, mappedItem, item, columnModel);
				}
				// @todo Cell adapter replace widget, remove this part if not usefull
				if(columnModel.widget) {
					var widgetClass = columnModel.widget.clazz,
						widgetJQueryPlugin = widgetClass._fullName,
						widgetOptions = columnModel.widget.options;

					// Ok it is costing : + z*n (z #columWidget; n #items) with this 
					// part to insert the items and render widget if there is
					var itemId = mappedItem[i].id;
					var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' span');
					widgetOptions.value = mappedItem[i][columnModel.name];
					$cell[widgetJQueryPlugin](widgetOptions);
					$cell[widgetJQueryPlugin]('render');
				}
			}
		},

		/**
		 * Refresh item
		 * @param {mad.model.Model} item The item to refresh
		 * @return {void}
		 */
		'refreshItem': function (item) {
			this.view.refreshItem(item);
		},

		/**
		 * Load items in the grid. If the grid contain items, empty it
		 * @param {mad.model.Model[]} items The array or list of items to insert in the grid
		 * @return {void}
		 */
		'load': function (items) {
			var self = this;
			this.empty();
			this.state.data = items;
			can.each(items, function (item, i) {
				self.insertItem(item);
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the mouse leave the main area of component. It does not include
		 * table header.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'tbody mouseleave': function (element, evt) {
			//
		},

		/**
		 * Observe when an item is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemSelected) {
				this.options.callbacks.itemSelected(el, ev, item, srcEvent);
			}
		},

		/**
		 * An item has been hovered
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The hovered item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_hovered': function (el, ev, item, srcEvent) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemHovered) {
				this.options.callbacks.itemHovered(el, ev, item, srcEvent);
			}
		}
	});

});