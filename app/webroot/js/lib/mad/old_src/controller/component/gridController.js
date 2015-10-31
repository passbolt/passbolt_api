steal(
	'jquery/controller',
	'mad/controller/componentController.js',
	'mad/model/grid/column.js',
	'mad/view/component/grid.js',
	'mad/object/map.js',
	'mad/view/template/component/grid.ejs',
	'mad/view/template/component/grid/gridItem.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.GridController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * @see mad.view.component.Grid
	 *
	 * The Grid class Controller is our implementation of the UI component grid.
	 * 
	 * ## Example
	 * @demo ./lib/mad/demo/controller/component/grid_controller.html
	 * 
	 * @constructor
	 * Creates a new Grid Controller Component
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 */
	mad.controller.ComponentController.extend('mad.controller.component.GridController', /** @static */	{

		'defaults': {
			'label': 'Grid Component',
			'viewClass': mad.view.component.Grid,
			'cssClasses': ['tableview'],
			'templateUri': 'mad/view/template/component/grid.ejs',
			'itemTemplateUri': 'mad/view/template/component/grid/gridItem.ejs',

			// The class of the item
			'itemClass': null,
			// the grid column model
			'columnModel': [],
			// the map to use to map JMVC model to the grid data model
			'map': null,
			// the top tag of the grid
			'tag': 'div',
			// callbacks associated to the events which could occured
			'callbacks': {
				'item_selected': null,
				'item_hovered': null
			}
		}

	}, /** @prototype */ {

		/**
		 * Reset the grid
		 * @return {void}
		 */
		'reset': function () {
			// @todo Check after this operation if the widget are well destroyed.
			// The hypothesis let's believe me than the remove function will deeply removed
			// each element and launch the destroy function of each component controller
			this.view.reset();
		},

		/**
		 * Before render.
		 */
		'beforeRender': function() {
			this._super();
			this.setViewData('columnModel', this.options.columnModel);
			this.setViewData('items', []);
		},

		/**
		 * Get the column model of the grid
		 * @return {mad.model.Model}
		 */
		'getColumnModel': function () {
			return this.options.columnModel;
		},


		/**
		 * Get the itemClass which represents the items managed by the component
		 * @return {mad.model.Model}
		 */
		'getItemClass': function () {
			return this.options.itemClass;
		},

		/**
		 * Set the itemClass which represents the items managed by the component
		 * @param {mad.model.Model} itemClass The item class
		 * @return {void}
		 */
		'setItemClass': function (itemClass) {
			this.options.itemClass = itemClass;
		},

		/**
		 * Get the associated map, which will be used to map the model data to the
		 * expected view format
		 * @return {mad.object.Map}
		 */
		'getMap': function (map) {
			return this.options.map;
		},

		/**
		 * Set the associated map, which will be used to map the model data to the
		 * expected view format
		 * @param {mad.object.Map} map The map
		 * @return {void}
		 */
		'setMap': function (map) {
			this.options.map = map;
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
			if (this.getItemClass() != null && !(item instanceof this.getItemClass())) {
				throw new mad.error.WrongParametersException('item', this.getItemClass().fullName);
			}

			var self = this,
				mappedItem = this.getMap().mapObject(item),
				columnModels = this.getColumnModel();

			// insert the item in the view
			this.view.insertItem(item, refItemId, position);

			// apply a widget to cells following the columns model
			for(var j in columnModels) {
				var columnModel = columnModels[j];

				if(columnModel.cellAdapter) {
					var itemId = mappedItem.id;
					var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
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
					var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
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
			
			var self = this,
				mappedItem = this.getMap().mapObject(item),
				columnModels = this.getColumnModel();

			// apply a widget to cells following the columns model
			for(var j in columnModels) {
				var columnModel = columnModels[j];

				if(columnModel.cellAdapter) {
					var itemId = mappedItem.id;
					var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
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
					var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
					widgetOptions.value = mappedItem[i][columnModel.name];
					$cell[widgetJQueryPlugin](widgetOptions);
					$cell[widgetJQueryPlugin]('render');
				}
			}
		},

		/**
		 * Load items in the grid. If the grid contain items, reset it
		 * @param {mad.model.Model[]} items The array or list of items to insert in the grid
		 * @return {void}
		 */
		'load': function (items) {
			var self = this;
			this.reset();
			can.each(items, function (item, i) {
				self.insertItem(item);
			});
			return this;
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