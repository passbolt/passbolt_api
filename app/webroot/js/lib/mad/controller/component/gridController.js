steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/view/component/grid.js',
	 MAD_ROOT+'/object/map.js'
)
.then(
	function($){
        
		/*
        * @class mad.controller.component.GridController
        * @inherits {mad.controller.ComponentController}
        * @parent index
		* @see {mad.view.component.Grid}
        * 
        * The Grid class Controller is our implementation of the UI component grid.
		* 
        * @constructor
        * Creates a new Grid Controller Component
		* @param {array} options Optional parameters
        * @return {mad.controller.component.GridController}
        */
		mad.controller.ComponentController.extend('mad.controller.component.GridController', 
		/** @static */
		{
			'defaults': {
				'label':			'Grid Component',
				'viewClass':		mad.view.component.Grid,
				'templateUri':		'//'+MAD_ROOT+'/view/template/component/grid.ejs'
			}
		},
		/** @prototype */
		{
			
			/**
             * The map to transform JMVC model object into jqgrid understable format
             * @type {mad.object.Map}
             */
			'map': null,
			
			// Construcor like
			'init': function(el, options)
			{
				this._super(el, options);
				this.map = this.options.map;
				this.setViewData('items', []);
			},
			
			/**
			 * Empty the grid
			 * @return {void}
			 */
			'empty': function() {
				// @todo Check after this operation if the widget are well destroyed.
				// The hypothesis let's believe me than the remove function will deeply removed
				// each element and launch the destroy function of each component controller
				this.view.empty();
			},
			
			/**
			 * Hide Column
			 * @param {string} columnName The column name to hide
			 * @return {void}
			 */
			'hideColumn': function(columnName) {
				this.view.hideColumn(columnName);
			},
			
			/**
			 * Show Column
			 * @param {string} columnName The column name to show
			 * @return {void}
			 */
			'showColumn': function(columnName) {
				this.view.showColumn(columnName);
			},
			
			/**
			 * Insert items in the grid
			 * @param {$.Model[]} items The array of items to insert in the grid
			 * @param {string} position The position to insert the new items.
			 * Allowed inside_replace, first, last, before, after
			 * @param {string} refId The reference item id to position the new ones
			 * @return {void}
			 */
			'insertItems': function(items, position, refId){
				var mappedData = this.map.mapObjects(items),				// map items to the view format
					self = this;
					
				// insert items in the view
				this.view.insertItems(mappedData);
				
				// apply a widget to cells following the columns model
				for(var j in this.options.columnModel){
					var columnModel = this.options.columnModel[j];
					
					if(columnModel.cellAdapter){
						for(var i in mappedData){
							var itemId = mappedData[i].id;
							var $cell = $('#'+itemId+' .'+columnModel.name+' span');
							var cellValue = mappedData[i][columnModel.name];
							columnModel.cellAdapter($cell, cellValue);
						}
					}
					// @todo Cell adapter replace widget, remove this part if not usefull
					if(columnModel.widget){
						var widgetClass = columnModel.widget.clazz,
							widgetJQueryPlugin = widgetClass._fullName,
							widgetOptions = columnModel.widget.options;
						
						// Ok it is costing : + z*n (z #columWidget; n #items) with this 
						// part to insert the items and render widget if there is
						for(var i in mappedData){
							var itemId = mappedData[i].id;
							var $cell = $('#'+itemId+' .'+columnModel.name+' span');
							widgetOptions.value = mappedData[i][columnModel.name];
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
			'load': function(items)
			{
				this.empty();
				this.insertItems(items);
			},			
			
			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */
			
			/**
			 * An item has been selected
			 * @return {void}
			 */
			'item_selected': function(row, event) {  },
			
			/**
			 * An item has been hovered
			 * @return {void}
			 */
			'item_hovered': function(row, event) {  }
		});
        
	}
);
