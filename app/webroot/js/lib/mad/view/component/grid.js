steal( 
    MAD_ROOT+'/view',
	'lib/jqgrid/jquery.jqGrid.min.js',
	'lib/jqgrid/ui.jqgrid.css'
)
.then( 
    MAD_ROOT+'/view/template/component/grid.ejs',
    function($){
        		
        /*
         * @class mad.view.component.Grid
		 * @inherits mad.view.View
         * @parent index
		 * 
         * @constructor
         * 
         * @return {mad.view.component.Grid}
        */
        mad.view.View.extend('mad.view.component.Grid', 
		/** @static */
        { }
		
		/** @prototype */
		, {
			
			'$jqgrid': null,
			
			// Constructor like
			'init':function(controller, options)
			{
				this._super(controller, options);
			},
			
			/**
			 * An item has been selected
			 * @return {void}
			 */
			'itemSelected': function(itemId, obj)
			{
				this.element.trigger('item_selected', itemId);
			},
			
			/**
			 *
			 */
			'load': function(items){
				for(var i = 0; i<items.length; i++){
					this.$jqgrid.addRowData(items[i].id, items[i], 'last');
				}
			},
			
			/**
			 * Render the jstree component
			 * @return {void}
			 */
			'render': function()
			{
				var self = this;
				this._super();
				
				this.$jqgrid = this.element.find('table');
				this.$jqgrid.jqGrid({
//					url:'example.php',
					datatype: 'local',
					mtype: 'GET',
					colNames:this.controller.options.columnNames,
					colModel :this.controller.options.columnModel,
//					pager: '#pager',
					rowNum:0,
					rowList:[10,20,30],
//					sortname: 'id',
//					sortorder: 'desc',
					viewrecords: true,
					gridview: true,
					caption: 'My first grid',
					onSelectRow:function(rowId, status, e){
						self.itemSelected(rowId);
					}
				});
			}
		});
	}	
);