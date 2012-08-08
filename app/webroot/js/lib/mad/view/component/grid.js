steal( 
    MAD_ROOT+'/view',
//	'lib/jqgrid/jquery.jqGrid.min.js',
//	'lib/jqgrid/ui.jqgrid.css'
	MAD_ROOT+'/controller/component/gridCellController.js'
)
.then( 
    MAD_ROOT+'/view/template/component/grid.ejs',
    function($){
        		
        /*
         * @class mad.view.component.Grid
		 * @inherits mad.view.View
         * @parent index
		 * 
		 * Our implementation of the view grid component.
		 * 
         * @constructor
         * Creates a grid view
         * @return {mad.view.component.Grid}
        */
        mad.view.View.extend('mad.view.component.Grid', 
		/** @static */
        { }
		
		/** @prototype */
		, {
			
			// Constructor like
			'init':function(controller, options)
			{
				this._super(controller, options);
			},
			
			/**
			 * Empty the grid
			 * @return {void}
			 */
			'empty': function(){
				$('tbody tr', this.element).remove();
			},
			
			/**
			 * Get item id of a given row
			 * @param {jQuery} row The row element
			 * @return {string} the item id
			 */
			'getItemId': function(row){
				return row[0].id;
			},
			
			/**
			 * Hide Column
			 * @param {string} columnName The column name to hide
			 * @return {void}
			 */
			'hideColumn': function(columnName) {
				$('#'+columnName, this.element).hide();
				$('.'+columnName, this.element).hide();
			},
			
			/**
			 * Show Column
			 * @param {string} columnName The column name to show
			 * @return {void}
			 */
			'showColumn': function(columnName) {
				$('#'+columnName, this.element).show();
				$('.'+columnName, this.element).show();
			},
			
			/**
			 * Insert items in the grid
			 * @param {array} items The array of items to insert in the grid
			 * @param {string} position The position to insert the new items.
			 * Allowed first, last, before, after
			 * @param {string} refId The reference item id to position the new ones
			 * @return {void}
			 */
			'insertItems': function(items, position, refId){
				var $tbody = $('tbody', this.$grid),
					position = position || 'last',
					$ref = refId ? $('#'+refId, this.element) : null;
				
				if(!$.isArray(items)){
					items = [items];
				}
				
				for(var i in items){
					var item = items[i],
						rowContent = '<tr id="'+item.id+'">';

					// insert column data
					for(var j in this.controller.options.columnModel){
						var columnModel = this.controller.options.columnModel[j],
							cssClass = columnModel.name,
							cellValue = null;

						// A column adapater function is provided
						if(columnModel.valueAdapter) {
							cellValue = columnModel.valueAdapter(item[columnModel.name], item, columnModel, i);
						} 
						// A widget will take care of the cell rendering
						else if(columnModel.widget || columnModel.cellAdapter) {
							cellValue = '';
						} 
						// Else display the column value
						else {
							cellValue = item[columnModel.name];
						}

						// append the cell to the row
						rowContent += '<td class="'+cssClass+'"><span>'+cellValue+'</span></td>';
					}

					rowContent += '</tr>'

					// insert the row
					switch(position){
						case 'first':
							var $row = $(rowContent).prependTo($tbody);
							break;

						case 'last':
							var $row = $(rowContent).appendTo($tbody);
							break;

						case 'before':
							var $row = $(rowContent).insertBefore($ref);
							break;

						case 'after':
							var $row = $(rowContent).insertAfter($ref);
							break;
					}
				}
			},
			
			/**
			 * bind events... temporary, look inside comments
			 */
			'bindEvents': function()
			{
				var self = this;
				// @todo Ca c'est pas bien, comme ce n'est pas un controller, le binding ne sera pas detruit lors de la destruction du composant
				// Faire heriter view de controller plutot que de class fait du sens ici
				$('tr', this.element).live('click', function(event){
					var $row = $(this);
					var itemId = self.getItemId($row);
					self.element.trigger('item_selected', itemId);
				});
				$('tr', this.element).live('hover', function(event){
					var $row = $(this);
					var itemId = self.getItemId($row);
					self.element.trigger('item_hovered', itemId);
				});
			},
			
			/**
			 * Render the jstree component
			 * @return {void}
			 */
			'render': function()
			{
				var self = this;
				
				this._super();
				this.$grid = this.element.find('table');
				
				var $header = $('thead tr:first', this.$grid),
					columnNames = this.controller.options.columnNames;
				
				for(var i in this.controller.options.columnModel){
					var columnModel = this.controller.options.columnModel[i];
					// add column header
					$header.append('<th id="'+columnModel.name+'">'+columnNames[i]+'</th>');
				}
				
				this.bindEvents();
				
//				this.$jqgrid = this.element.find('table');
//				this.$jqgrid.jqGrid({
////					url:'example.php',
//					datatype: 'local',
//					mtype: 'GET',
//					colNames:this.controller.options.columnNames,
//					colModel :this.controller.options.columnModel,
////					pager: '#pager',
//					rowNum:0,
//					rowList:[10,20,30],
////					sortname: 'id',
////					sortorder: 'desc',
//					viewrecords: true,
//					gridview: true,
//					caption: 'My first grid',
//					onSelectRow:function(rowId, status, e){
//						self.itemSelected(rowId);
//					}
//				});
			}
			
		});
	}	
);