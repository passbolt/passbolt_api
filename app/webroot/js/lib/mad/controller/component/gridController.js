steal(
	'jquery/controller',
	MAD_ROOT + '/controller/componentController.js',
	MAD_ROOT + '/view/component/grid.js',
	MAD_ROOT + '/object/map.js'
).then(function ($) {

	/*
	 * @class mad.controller.component.GridController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
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
			'templateUri': '//' + MAD_ROOT + '/view/template/component/grid.ejs',
			// the grid column names
			'columnNames': [],
			// the grid column model
			'columnModel': [],
			// the map to use to map JMVC model to the grid data model
			'map': null,
			// the top tag of the grid
			'tag': 'table'
		},
		'listensTo': ['item_selected', 'item_unselected', 'item_hovered']

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
		 * Delete an item in the grid
		 * @param {string} itemId The item to delete
		 * @return {void}
		 */
		'deleteItems': function (itemId) {
			// insert items in the view
			this.view.deleteItems(itemId);
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
		'insertItems': function (items, refItemId, position) {
			var self = this;
			items = !$.isArray(items) ? [items] : items;
			var mappedItems = this.map.mapObjects(items);

			// insert items in the view
			this.view.insertItems(items, refItemId, position);

			// apply a widget to cells following the columns model
			for(var j in this.options.columnModel) {
				var columnModel = this.options.columnModel[j];

				if(columnModel.cellAdapter) {
					for(var i in mappedItems) {
						var itemId = mappedItems[i].id;
						var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' span');
						var cellValue = mappedItems[i][columnModel.name];
						columnModel.cellAdapter($cell, cellValue);
					}
				}
				// @todo Cell adapter replace widget, remove this part if not usefull
				if(columnModel.widget) {
					var widgetClass = columnModel.widget.clazz,
						widgetJQueryPlugin = widgetClass._fullName,
						widgetOptions = columnModel.widget.options;

					// Ok it is costing : + z*n (z #columWidget; n #items) with this 
					// part to insert the items and render widget if there is
					for(var i in mappedItems) {
						var itemId = mappedItems[i].id;
						var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' span');
						widgetOptions.value = mappedItems[i][columnModel.name];
						$cell[widgetJQueryPlugin](widgetOptions);
						$cell[widgetJQueryPlugin]('render');
					}
				}
			}
		},

		/**
		 * Load items in the grid. If the grid contain items, empty it
		 * @param {$.Model[]} items The array of items to insert in the grid
		 * @return {void}
		 */
		'load': function (items) {
			this.empty();
			this.state.data = items;
			this.insertItems(items);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the mouse leave the component
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'tbody mouseleave': function (element, evt) {
			if (this.crtFocusedResourceId) {
				mad.eventBus.trigger('resource_unfocused', {
					'id': this.crtFocusedResourceId
				});
				this.crtFocusedResourceId = null;
			}
		},

		/**
		 * Observe when a resource is hovered
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} data The hovered resource id
		 * @return {void}
		 */
		'item_hovered': function (row, event, itemId) {},

		/**
		 * Observe when an resource is selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} data The selected resource id
		 * @return {void}
		 */
		'item_selected': function (row, event, itemId) {}
	});

});